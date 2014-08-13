<?php
namespace Codeception\Module;

use Codeception\Configuration as Configuration;
use Codeception\Exception\Module as ModuleException;
use Codeception\Exception\ModuleConfig as ModuleConfigException;
use Codeception\Lib\Driver\ExtendedDbDriver as Driver;
use tad\wordpress\maker\CommentMaker;
use tad\wordpress\maker\PostMaker;
use tad\wordpress\maker\UserMaker;

/**
 * An extension of Codeception Db class to add WordPress database specific
 * methods.
 */
class WPDb extends ExtendedDb
{
    /**
     * The module required configuration parameters.
     *
     * url - the site url
     *
     * @var array
     */
    protected $requiredFields = array('url');
    /**
     * The module optional configuration parameters.
     *
     * tablePrefix - the WordPress installation table prefix, defaults to "wp".
     * checkExistence - if true will attempt to insert coherent data in the database; e.g. an post with term insertion will trigger post and term insertions before the relation between them is inserted; defaults to false.
     * update - if true have... methods will attempt an update on duplicate keys; defaults to true.
     *
     * @var array
     */
    protected $config = array('tablePrefix' => 'wp', 'checkExistence' => false, 'update' => true);
    /**
     * The table prefix to use.
     *
     * @var string
     */
    protected $tablePrefix = 'wp_';

    /**
     * Initializes the module.
     *
     * @return void
     */
    public function _initialize()
    {
        if ($this->config['dump'] && ($this->config['cleanup'] or ($this->config['populate']))) {

            if (!file_exists(Configuration::projectDir() . $this->config['dump'])) {
                throw new ModuleConfigException(
                    __CLASS__,
                    "\nFile with dump doesn't exist.
                    Please, check path for sql file: " . $this->config['dump']
                );
            }
            $sql = file_get_contents(Configuration::projectDir() . $this->config['dump']);
            $sql = preg_replace('%/\*(?!!\d+)(?:(?!\*/).)*\*/%s', "", $sql);
            $this->sql = explode("\n", $sql);
        }

        try {
            $this->driver = Driver::create($this->config['dsn'], $this->config['user'], $this->config['password']);
        } catch (\PDOException $e) {
            throw new ModuleException(__CLASS__, $e->getMessage() . ' while creating PDO connection');
        }

        $this->dbh = $this->driver->getDbh();

        // starting with loading dump
        if ($this->config['populate']) {
            $this->cleanup();
            $this->loadDump();
            $this->populated = true;
        }
        $this->tablePrefix = $this->config['tablePrefix'];
    }

    /**
     * Inserts a user and appropriate meta in the database.
     *
     * @param  string $user_login The user login slug
     * @param  int $user_id The user ID.
     * @param  string $role The user role slug, e.g. "administrator"; defaults to "subscriber".
     * @param  array $userData An associative array of column names and values overridind defaults in the "users" and "usermeta" table.
     *
     * @return void
     */
    public function haveUserInDatabase($user_login, $user_id, $role = 'subscriber', array $userData = array())
    {
        // get the user
        list($userLevelDefaults, $userTableData, $userCapabilitiesData) = UserMaker::makeUser($user_login, $user_id, $role, $userData);
        $this->debugSection('Makers output', print_r($userTableData));
        // add the data to the database
        $tableName = $this->getPrefixedTableNameFor('users');
        $this->haveInDatabase($tableName, $userTableData);
        $tableName = $this->getPrefixedTableNameFor('usermeta');
        $this->haveInDatabase($tableName, $userCapabilitiesData);
        $this->haveInDatabase($tableName, $userLevelDefaults);
    }

    /**
     * Returns a prefixed table name.
     *
     * @param  string $tableName The table name, e.g. "users".
     *
     * @return string            The prefixed table name, e.g. "wp_users".
     */
    protected function getPrefixedTableNameFor($tableName)
    {
        $tableName = $this->config['tablePrefix'] . $tableName;
        return $tableName;
    }

    /**
     * Inserts or updates a database entry.
     *
     * An override of the parent method to allow back compatibililty and configuration based use.
     *
     * @param  string $table The table name.
     * @param  array $data An associative array of the column names and values to insert.
     *
     * @return void
     */
    public function haveInDatabase($table, array $data)
    {
        $this->debugSection('Configuration', sprintf('Update setting set to %s', $this->config['update']));
        if (isset($this->config['update']) and $this->config['update']) {
            return parent::haveOrUpdateInDatabase($table, $data);
        }
        return parent::haveInDatabase($table, $data);
    }

    /**
     * Checks if an option is in the database and is serialized.
     *
     * Will look in the "options" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function seeSerializedOptionInDatabase($criteria)
    {
        if (isset($criteria['option_value'])) {
            $criteria['option_value'] = @serialize($criteria['option_value']);
        }
        $this->seeOptionInDatabase($criteria);
    }

    /**
     * Checks if an option is in the database.
     *
     * Will look in the "options" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function seeOptionInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('options');
        $this->seeInDatabase($tableName, $criteria);
    }

    /**
     * Checks that a serialized option is not in the database.
     *
     * Will look in the "options" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function dontSeeSerializedOptionInDatabase($criteria)
    {
        if (isset($criteria['option_value'])) {
            $criteria['option_value'] = @serialize($criteria['option_value']);
        }
        $this->dontSeeOptionInDatabase($criteria);
    }

    /**
     * Checks that an option is not in the database.
     *
     * Will look in the "options" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function dontSeeOptionInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('options');
        $this->dontSeeInDatabase($tableName, $criteria);
    }

    /**
     * Checks for a post meta value in the database.
     *
     * Will look up the "postmeta"  table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function seePostMetaInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('postmeta');
        $this->seeInDatabase($tableName, $criteria);
    }

    /**
     * Inserts a link in the database.
     *
     * Will insert in the "links" table.
     *
     * @param  int $link_id The link id to insert.
     * @param  array $data The data to insert.
     *
     * @return void
     */
    public function haveLinkInDatabase($link_id, array $data = array())
    {
        if (!is_int($link_id)) {
            throw new \BadMethodCallException('Link id must be an int');
        }
        $tableName = $this->getPrefixedTableNameFor('links');
        $data = array_merge($data, array('link_id' => $link_id));
        $this->haveInDatabase($tableName, $data);
    }

    /**
     * Checks for a link in the database.
     *
     * Will look up the "links" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function seeLinkInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('links');
        $this->seeInDatabase($tableName, $criteria);
    }

    /**
     * Checks for a link is not in the database.
     *
     * Will look up the "links" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function dontSeeLinkInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('links');
        $this->dontSeeInDatabase($tableName, $criteria);
    }

    /**
     * Checks that a post meta value is not there.
     *
     * Will look up the "postmeta" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function dontSeePostMetaInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('postmeta');
        $this->dontSeeInDatabase($tableName, $criteria);
    }

    /**
     * Checks that a post to term relation exists in the database.
     *
     * Will look up the "term_relationships" table.
     *
     * @param  int $post_id The post ID.
     * @param  int $term_id The term ID.
     * @param  integer $term_order The order the term applies to the post, defaults to 0.
     *
     * @return void
     */
    public function seePostWithTermInDatabase($post_id, $term_id, $term_order = 0)
    {
        $tableName = $this->getPrefixedTableNameFor('term_relationships');
        $this->dontSeeInDatabase($tableName, array('object_id' => $post_id, 'term_id' => $term_id, 'term_order' => $term_order));
    }

    /**
     * Checks that a user is in the database.
     *
     * Will look up the "users" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function seeUserInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('users');
        $this->seeInDatabase($tableName, $criteria);
    }

    /**
     * Checks that a user is not in the database.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function dontSeeUserInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('users');
        $this->dontSeeInDatabase($tableName, $criteria);
    }

    /**
     * Inserts a post in the database.
     *
     * @param  int $ID The post ID.
     * @param  array $data An associative array of post data to override default and random generated values.
     *
     * @return void
     */
    public function havePostInDatabase($ID, array $data = array())
    {
        $post = PostMaker::makePost($ID, $this->config['url'], $data);
        $tableName = $this->getPrefixedTableNameFor('posts');
        $this->haveInDatabase($tableName, $post);
    }

    /**
     * Inserts a post in the database.
     *
     * @param  int $ID The post ID.
     * @param  array $data An associative array of post data to override default and random generated values.
     *
     * @return void
     */
    public function havePageInDatabase($ID, array $data = array())
    {
        $post = PostMaker::makePage($ID, $this->config['url'], $data);
        $tableName = $this->getPrefixedTableNameFor('posts');
        $this->haveInDatabase($tableName, $post);
    }

    /**
     * Checks for a post in the database.
     *
     * Will look up the "posts" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function seePostInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('posts');
        $this->seeInDatabase($tableName, $criteria);
    }

    /**
     * Checks that a post is not in the database.
     *
     * Will look up the "posts" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function dontSeePostInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('posts');
        $this->dontSeeInDatabase($tableName, $criteria);
    }

    /**
     * Checks for a page in the database.
     *
     * Will look up the "posts" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function seePageInDatabase(array $criteria)
    {
        $criteria = array_merge($criteria, array('post_type' => 'page'));
        $tableName = $this->getPrefixedTableNameFor('posts');
        $this->seeInDatabase($tableName, $criteria);
    }

    /**
     * Checks that a page is not in the database.
     *
     * Will look up the "posts" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function dontSeePageInDatabase(array $criteria)
    {
        $criteria = array_merge($criteria, array('post_type' => 'page'));
        $tableName = $this->getPrefixedTableNameFor('posts');
        $this->dontSeeInDatabase($tableName, $criteria);
    }

    /**
     * Checks that some user meta value is in the database.
     *
     * Will look up the "usermeta" table.
     *
     * @param  int $user_id The user ID.
     * @param  string $meta_key
     * @param  string /int $meta_value
     * @param  int $umeta_id The optional user meta id.
     *
     * @return void
     */
    public function haveUserMetaInDatabase($user_id, $meta_key, $meta_value, $umeta_id = null)
    {
        if (!is_int($user_id)) {
            throw new \BadMethodCallException('User id must be an int', 1);
        }
        if (!is_string($meta_key) or !is_string($meta_value)) {
            throw new \BadMethodCallException('Meta key and value must be strings', 2);
        }
        if (!is_null($umeta_id) and !is_int($umeta_id)) {
            throw new \BadMethodCallException('User meta id must either be an int or null', 3);
        }
        $this->maybeCheckUserExistsInDatabase($user_id);
        $tableName = $this->getPrefixedTableNameFor('usermeta');
        $this->haveInDatabase($tableName, array(
            'umeta_id' => $umeta_id,
            'user_id' => $user_id,
            'meta_key' => $meta_key,
            'meta_value' => $meta_value
        ));
    }

    /**
     * Conditionally checks for a user in the database.
     *
     * Will look up the "users" table, will throw if not found.
     *
     * @param  int $user_id The user ID.
     *
     * @return void
     */
    protected function maybeCheckUserExistsInDatabase($user_id)
    {
        if (!isset($this->config['checkExistence']) or false == $this->config['checkExistence']) {
            return;
        }
        $tableName = $this->getPrefixedTableNameFor('users');
        if (!$this->grabFromDatabase($tableName, 'ID', array('ID' => $user_id))) {
            throw new \RuntimeException("A user with an id of $user_id does not exist", 1);
        }
    }

    /**
     * Inserts a link to term relationship in the database.
     *
     * If "checkExistence" then will make some checks for missing term and/or link.
     *
     * @param  int $link_id The link ID.
     * @param  int $term_id The term ID.
     * @param  integer $term_order An optional term order value, will default to 0.
     *
     * @return void
     */
    public function haveLinkWithTermInDatabase($link_id, $term_id, $term_order = 0)
    {
        if (!is_int($link_id) or !is_int($term_id) or !is_int($term_order)) {
            throw new \BadMethodCallException("Link ID, term ID and term order must be strings", 1);
        }
        $this->maybeCheckLinkExistsInDatabase($post_id);
        $this->maybeCheckTermExistsInDatabase($term_id);
        // add the relationship in the database
        $tableName = $this->getPrefixedTableNameFor('term_relationships');
        $this->haveInDatabase($tableName, array('object_id' => $link_id, 'term_taxonomy_id' => $term_id, 'term_order' => $term_order));
    }

    /**
     * Conditionally check for a link in the database.
     *
     * Will look up the "links" table, will throw if not found.
     *
     * @param  int $link_id The link ID.
     *
     * @return bool True if the link exists, false otherwise.
     */
    protected function maybeCheckLinkExistsInDatabase($link_id)
    {
        if (!isset($this->config['checkExistence']) or false == $this->config['checkExistence']) {
            return;
        }
        $tableName = $this->getPrefixedTableNameFor('links');
        if (!$this->grabFromDatabase($tableName, 'link_id', array('link_id' => $link_id))) {
            throw new \RuntimeException("A link with an id of $link_id does not exist", 1);
        }
    }

    /**
     * Conditionally checks that a term exists in the database.
     *
     * Will look up the "terms" table, will throw if not found.
     *
     * @param  int $term_id The term ID.
     *
     * @return void
     */
    protected function maybeCheckTermExistsInDatabase($term_id)
    {
        if (!isset($this->config['checkExistence']) or false == $this->config['checkExistence']) {
            return;
        }
        $tableName = $this->getPrefixedTableNameFor('terms');
        if (!$this->grabFromDatabase($tableName, 'term_id', array('term_id' => $term_id))) {
            throw new \RuntimeException("A term with an id of $term_id does not exist", 1);
        }
    }

    /**
     * Inserts a comment in the database.
     *
     * @param  int $comment_ID The comment ID.
     * @param  int $comment_post_ID The id of the post the comment refers to.
     * @param  array $data The comment data overriding default and random generated values.
     *
     * @return void
     */
    public function haveCommentInDatabase($comment_ID, $comment_post_ID, array $data = array())
    {
        if (!is_int($comment_ID) or !is_int($comment_post_ID)) {
            throw new \BadMethodCallException('Comment id and post id must be int', 1);
        }
        $comment = CommentMaker::makeComment($comment_ID, $comment_post_ID, $data);
        $tableName = $this->getPrefixedTableNameFor('comments');
        $this->haveInDatabase($tableName, $comment);
    }

    /**
     * Checks for a comment in the database.
     *
     * Will look up the "comments" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function seeCommentInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('comments');
        $this->seeInDatabase($tableName, $criteria);
    }


    /**
     * Checks that a comment is not in the database.
     *
     * Will look up the "comments" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function dontSeeCommentInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('comments');
        $this->dontSeeInDatabase($tableName, $criteria);
    }

    /**
     * Checks that a comment meta value is in the database.
     *
     * Will look up the "commentmeta" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function seeCommentMetaInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('commentmeta');
        $this->dontSeeInDatabase($tableName, $criteria);
    }

    /**
     * Checks that a comment meta value is not in the database.
     *
     * Will look up the "commentmeta" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function dontSeeCommentMetaInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('commentmeta');
        $this->dontSeeInDatabase($tableName, $criteria);
    }

    /**
     * Inserts a post to term relationship in the database.
     *
     * Will conditionally check for post and term existence if "checkExistence" is set to true.
     *
     * @param  int $post_id The post ID.
     * @param  int $term_id The term ID.
     * @param  integer $term_order The optional term order.
     *
     * @return void
     */
    public function havePostWithTermInDatabase($post_id, $term_id, $term_order = 0)
    {
        if (!is_int($post_id) or !is_int($term_id) or !is_int($term_order)) {
            throw new \BadMethodCallException("Post ID, term ID and term order must be strings", 1);
        }
        $this->maybeCheckPostExistsInDatabase($post_id);
        $this->maybeCheckTermExistsInDatabase($term_id);
        // add the relationship in the database
        $tableName = $this->getPrefixedTableNameFor('term_relationships');
        $this->haveInDatabase($tableName, array('object_id' => $post_id, 'term_taxonomy_id' => $term_id, 'term_order' => $term_order));
    }

    /**
     * Conditionally checks that a post exists in database, will throw if not existent.
     *
     * @param  int $post_id The post ID.
     *
     * @return void
     */
    protected function maybeCheckPostExistsInDatabase($post_id)
    {
        if (!isset($this->config['checkExistence']) or false == $this->config['checkExistence']) {
            return;
        }
        $tableName = $this->getPrefixedTableNameFor('posts');
        if (!$this->grabFromDatabase($tableName, 'ID', array('ID' => $post_id))) {
            throw new \RuntimeException("A post with an id of $post_id does not exist", 1);
        }
    }

    /**
     * Inserts a commment meta value in the database.
     *
     * @param  int $comment_id The comment ID.
     * @param  string $meta_key
     * @param  string /int $meta_value
     * @param  int $meta_id The optinal meta ID.
     *
     * @return void
     */
    public function haveCommentMetaInDatabase($comment_id, $meta_key, $meta_value, $meta_id = null)
    {
        if (!is_int($comment_id)) {
            throw new \BadMethodCallException('Comment id must be an int', 1);
        }
        if (!is_null($meta_id) and !is_int($meta_key)) {
            throw new \BadMethodCallException('Meta id must be either null or an int', 2);
        }
        if (!is_string($meta_key)) {
            throw new \BadMethodCallException('Meta key must be an string', 3);
        }
        if (!is_string($meta_value)) {
            throw new \BadMethodCallException('Meta value must be an string', 4);
        }
        $this->maybeCheckCommentExistsInDatabase($comment_id);
        $tableName = $this->getPrefixedTableNameFor('commmentmeta');
        $this->haveInDatabase($tableName, array('meta_id' => $meta_id, 'comment_id' => $comment_id, 'meta_key' => $meta_key, 'meta_value' => $meta_value));
    }

    /**
     * Conditionally checks that a comment exists in database, will throw if not existent.
     *
     * @param  int $commment_id The comment ID.
     *
     * @return void
     */
    protected function maybeCheckCommentExistsInDatabase($comment_id)
    {
        if (!isset($this->config['checkExistence']) or false == $this->config['checkExistence']) {
            return;
        }
        $tableName = $this->getPrefixedTableNameFor('comments');
        if (!$this->grabFromDatabase($tableName, 'comment_ID', array('commment_ID' => $comment_id))) {
            throw new \RuntimeException("A comment with an id of $comment_id does not exist", 1);
        }
    }

    /**
     * Inserts a post meta value in the database.
     *
     * Will check for post existence if "checkExistence" set to true.
     *
     * @param  int $post_id
     * @param  string $meta_key
     * @param  string /int $meta_value
     * @param  int $meta_id The optional meta ID.
     *
     * @return void
     */
    public function havePostMetaInDatabase($post_id, $meta_key, $meta_value, $meta_id = null)
    {
        if (!is_int($post_id)) {
            throw new \BadMethodCallException('Post id must be an int', 1);
        }
        if (!is_null($meta_id) and !is_int($meta_key)) {
            throw new \BadMethodCallException('Meta id must be either null or an int', 2);
        }
        if (!is_string($meta_key)) {
            throw new \BadMethodCallException('Meta key must be an string', 3);
        }
        if (!is_string($meta_value)) {
            throw new \BadMethodCallException('Meta value must be an string', 4);
        }
        $this->maybeCheckPostExistsInDatabase($post_id);
        $tableName = $this->getPrefixedTableNameFor('postmeta');
        $this->haveInDatabase($tableName, array('meta_id' => $meta_id, 'post_id' => $post_id, 'meta_key' => $meta_key, 'meta_value' => $meta_value));
    }

    /**
     * Inserts a term in the database.
     *
     * @param  string $term The term scree name, e.g. "Fuzzy".
     * @param  int $term_id
     * @param  array $args Term arguments overriding default and generated ones.
     *
     * @return void
     */
    public function haveTermInDatabase($term, $term_id, array $args = array())
    {
        // term table entry
        $taxonomy = isset($args['taxonomy']) ? $args['taxonomy'] : 'category';
        $termsTableEntry = array(
            'term_id' => $term_id,
            'name' => $term,
            'slug' => isset($args['slug']) ? $args['slug'] : lcfirst(\tad_Str::hyphen($term)),
            'term_group' => isset($args['term_group']) ? $args['term_group'] : 0,
        );
        $tableName = $this->getPrefixedTableNameFor('terms');
        $this->haveInDatabase($tableName, $termsTableEntry);
        // term_taxonomy table entry
        $termTaxonomyTableEntry = array(
            'term_taxonomy_id' => isset($args['term_taxonomy_id']) ? $args['term_taxonomy_id'] : null,
            'term_id' => $term_id,
            'taxonomy' => $taxonomy,
            'description' => isset($args['description']) ? $args['description'] : '',
            'parent' => isset($args['parent']) ? $args['parent'] : 0,
            'count' => isset($args['count']) ? $args['count'] : 0
        );
        $tableName = $this->getPrefixedTableNameFor('term_taxonomy');
        $this->haveInDatabase($tableName, $termTaxonomyTableEntry);
    }

    /**
     * Checks for a term in the database.
     *
     * Will look up the "terms" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function seeTermInDatabase($criteria)
    {
        if (isset($criteria['description']) or isset($criteria['taxonomy'])) {
            // the matching will be attempted against the term_taxonomy table
            $termTaxonomyCriteria = array(
                'taxonomy' => isset($criteria['taxonomy']) ? $criteria['taxonomy'] : false,
                'description' => isset($criteria['description']) ? $criteria['description'] : false,
                'term_id' => isset($criteria['term_id']) ? $criteria['term_id'] : false
            );
            $termTaxonomyCriteria = array_filter($termTaxonomyCriteria);
            $tableName = $this->getPrefixedTableNameFor('term_taxonomy');
            $this->seeInDatabase($tableName, $termTaxonomyCriteria);
        } else {
            // the matching will be attempted against the terms table
            $tableName = $this->getPrefixedTableNameFor('terms');
            $this->seeInDatabase($tableName, $criteria);
        }
    }

    /**
     * Checks that a term is not in the database.
     *
     *  Will look up the "terms" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function dontSeeTermInDatabase($criteria)
    {
        if (isset($criteria['description']) or isset($criteria['taxonomy'])) {
            // the matching will be attempted against the term_taxonomy table
            $termTaxonomyCriteria = array(
                'taxonomy' => isset($criteria['taxonomy']) ? $criteria['taxonomy'] : false,
                'description' => isset($criteria['description']) ? $criteria['description'] : false,
                'term_id' => isset($criteria['term_id']) ? $criteria['term_id'] : false
            );
            $termTaxonomyCriteria = array_filter($termTaxonomyCriteria);
            $tableName = $this->getPrefixedTableNameFor('term_taxonomy');
            $this->dontSeeInDatabase($tableName, $termTaxonomyCriteria);
        }
        // the matching will be attempted against the terms table
        $tableName = $this->getPrefixedTableNameFor('terms');
        $this->dontSeeInDatabase($tableName, $criteria);
    }

    /**
     * Checks for a user meta value in the database.
     *
     * Will look up the "usermeta" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function seeUserMetaInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('usermeta');
        $this->seeInDatabase($tableName, $criteria);
    }

    /**
     * Check that a user meta value is not in the database.
     *
     * Will look up the "usermeta" table.
     *
     * @param  array $criteria
     *
     * @return void
     */
    public function dontSeeUserMetaInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('usermeta');
        $this->dontSeeInDatabase($tableName, $criteria);
    }

    /**
     * Inserts a serialized option in the database.
     *
     * @param  string $option_name
     * @param  string /int $option_value
     *
     * @return void
     */
    public function haveSerializedOptionInDatabase($option_name, $option_value)
    {
        $serializedOptionValue = @serialize($option_value);
        return $this->haveOptionInDatabase($option_name, $serializedOptionValue);
    }

    /**
     * Inserts an option in the database.
     *
     * @param  string $option_name
     * @param  string /int $option_value
     *
     * @return void
     */
    public function haveOptionInDatabase($option_name, $option_value)
    {
        $tableName = $this->getPrefixedTableNameFor('options');
        return $this->haveInDatabase($tableName, array('option_name' => $option_name, 'option_value' => $option_value, 'autoload' => 'yes'));
    }

    /**
     * Removes an entry from the commentmeta table.
     *
     * @param  array  $criteria
     *
     * @return void
     */
    public function dontHaveCommentMetaInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('commentmeta');
        $this->dontHaveInDatabase($tableName, $criteria);
    }

    /**
     * Removes an entry from the comment table.
     *
     * @param  array  $criteria
     *
     * @return void
     */
    public function dontHaveCommentInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('comments');
        $this->dontHaveInDatabase($tableName, $criteria);
    }

    /**
     * Removes an entry from the links table.
     *
     * @param  array  $criteria
     *
     * @return void
     */
    public function dontHaveLinkInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('links');
        $this->dontHaveInDatabase($tableName, $criteria);
    }

    /**
     * Removes an entry from the options table.
     *
     * @param  array  $criteria
     *
     * @return void
     */
    public function dontHaveOptionInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('options');
        $this->dontHaveInDatabase($tableName, $criteria);
    }

    /**
     * Removes an entry from the postmeta table.
     *
     * @param  array  $criteria
     *
     * @return void
     */
    public function dontHavePostMetaInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('postmeta');
        $this->dontHaveInDatabase($tableName, $criteria);
    }

    /**
     * Removes an entry from the posts table.
     *
     * @param  array  $criteria
     *
     * @return void
     */
    public function dontHavePostInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('posts');
        $this->dontHaveInDatabase($tableName, $criteria);
    }

    /**
     * Removes an entry from the term_relationships table.
     *
     * @param  array  $criteria
     *
     * @return void
     */
    public function dontHaveTermRelationshipInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('term_relationships');
        $this->dontHaveInDatabase($tableName, $criteria);
    }

    /**
     * Removes an entry from the term_taxonomy table.
     *
     * @param  array  $criteria
     *
     * @return void
     */
    public function dontHaveTermTaxonomyInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('term_taxonomy');
        $this->dontHaveInDatabase($tableName, $criteria);
    }

    /**
     * Removes an entry from the terms table.
     *
     * @param  array  $criteria
     *
     * @return void
     */
    public function dontHaveTermInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('terms');
        $this->dontHaveInDatabase($tableName, $criteria);
    }

    /**
     * Removes an entry from the usermeta table.
     *
     * @param  array  $criteria
     *
     * @return void
     */
    public function dontHaveUserMetaInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('usermeta');
        $this->dontHaveInDatabase($tableName, $criteria);
    }

    /**
     * Removes an entry from the users table.
     *
     * @param  array  $criteria
     *
     * @return void
     */
    public function dontHaveUserInDatabase(array $criteria)
    {
        $tableName = $this->getPrefixedTableNameFor('users');
        $this->dontHaveInDatabase($tableName, $criteria);
    }
}
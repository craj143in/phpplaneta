<?php

/**
 * The tests depend on the seed data from phpplaneta_sqlite_data.sql
 * that data is already loaded into phpplaneta.sqlite.init database
 * 
 * The seed data contains:
 *      - 12 news, the first 10 is active, the last 2 are inactive
 *      - Each news' text contains "Lorem ipsum" dummy text only
 *      - 4 news categories, the first 3 have news, the 4th doesn't
 *      - 12 tags, each news has it's own tag
 *      - 1 user
 */

class NewsTest extends PHPUnit_Framework_TestCase {
    
    /**
     *
     * @var Planet_Model_News
     */
    protected $_model = null;
    
    public function setup() {
        $initDb = realpath(APPLICATION_PATH . '/../db/sqlite/phpplaneta.sqlite.init');
        $testDb = realpath(APPLICATION_PATH . '/../db/sqlite/phpplaneta.sqlite');
        copy($initDb, $testDb);
        
        $this->_model = new Planet_Model_News();
    }
    
    public function tearDown() {
        $initDb = realpath(APPLICATION_PATH . '/../db/sqlite/phpplaneta.sqlite.init');
        $testDb = realpath(APPLICATION_PATH . '/../db/sqlite/phpplaneta.sqlite');
        copy($initDb, $testDb);
    }
    
    public function testGetAllActiveNewsReturnsOnlyActiveNews() {
        $news = $this->_model->getAllActiveNews();
        
        $this->assertInstanceOf('Zend_Db_Table_Rowset_Abstract', $news);
        $this->assertEquals(10, count($news));
    }
    
    public function testGetAllActiveNewsReturnsOnlyActiveNewsPaginated() {
        $news = $this->_model->getAllActiveNews(1);
        
        $this->assertInstanceOf('Zend_Paginator', $news);
        $this->assertEquals(5, $news->getCurrentItemCount());
    }
    
    public function testSearchReturnsOnlyActiveNews() {
        $searchString = 'lorem ipsum';
        
        $news = $this->_model->searchActiveNews($searchString);
        
        $this->assertInstanceOf('Zend_Db_Table_Rowset_Abstract', $news);
        $this->assertEquals(10, count($news));
    }
    
    public function testSearchReturnsOnlyActiveNewsPaginated() {
        $searchString = 'lorem ipsum';
        
        $news = $this->_model->searchActiveNews($searchString, 1);
        
        $this->assertInstanceOf('Zend_Paginator', $news);
        $this->assertEquals(5, $news->getCurrentItemCount());
    }
    
    public function testGetAllNewsReturnsActiveAndInactiveNews() {
        $news = $this->_model->getAllNews();
        
        $this->assertInstanceOf('Zend_Db_Table_Rowset_Abstract', $news);
        $this->assertEquals(12, count($news));
    }
    
    public function testGetAllNewsReturnsActiveAndInactiveNewsPaginated() {
        $news = $this->_model->getAllNews(1);
        
        $this->assertInstanceOf('Zend_Paginator', $news);
        $this->assertEquals(5, $news->getCurrentItemCount());
    }
    
    public function testGetOneExistingActiveNewsBySlug() {
        $slug = 'slug-vesti-1';
        
        $news = $this->_model->getOneActiveNewsBySlug($slug);
        
        $this->assertEquals($slug, $news->slug);
    }
    
    /**
     * @expectedException PPN_Exception_NotFound
     */
    public function testGetOneExistingInactiveNewsBySlugThrowsException() {
        $slug = 'slug-vesti-11';
        
        $news = $this->_model->getOneActiveNewsBySlug($slug);
    }
    
    /**
     * @expectedException PPN_Exception_NotFound
     */
    public function testGetOneNonexistingNewsBySlug() {
        $slug = 'no-such-news';
        
        $news = $this->_model->getOneActiveNewsBySlug($slug);
    }
    
    public function testGetOneExistingNewsById() {
        $id = '1';
        
        $news = $this->_model->getOneNewsById($id);
        
        $this->assertEquals($id, $news->id);
    }
    
    /**
     * @expectedException PPN_Exception_NotFound
     */
    public function testGetOneNonexistingNewsById() {
        $id = '100';
        
        $news = $this->_model->getOneNewsById($id);
    }
    
}
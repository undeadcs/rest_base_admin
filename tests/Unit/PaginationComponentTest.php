<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use App\View\Components\Pagination;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginationComponentTest extends TestCase {
	public static int $pagesPerFrame = 15;
	public static int $lastPage = 63;
	public static int $total = 1060;
	
	public static function prevFrameProvider( ) : array {
		return [ [
			1, 0
		], [
			10, 0
		], [
			15, 0
		], [
			16, 15
		], [
			20, 15
		], [
			30, 15
		], [
			31, 30
		] ];
	}
	
	#[ DataProvider( 'prevFrameProvider' ) ]
	public function test_prev_frame( int $currentPage, int $expectedPageNumber ) : void {
		$paginator = $this->createConfiguredStub( LengthAwarePaginator::class, [
			'currentPage' => $currentPage
		] );
		$pagination = new Pagination( $paginator,  self::$pagesPerFrame );
		
		$this->assertEquals( $expectedPageNumber, $pagination->PrevFrame( ) );
	}
	
	public static function nextFrameProvider( ) : array {
		return [ [
			1, 16
		], [
			10, 16
		], [
			15, 16
		], [
			16, 31
		], [
			20, 31
		], [
			30, 31
		], [
			31, 46
		], [
			61, 0
		] ];
	}
	
	#[ DataProvider( 'nextFrameProvider' ) ]
	public function test_next_frame( int $currentPage, int $expectedPageNumber ) : void {
		$paginator = $this->createConfiguredStub( LengthAwarePaginator::class, [
			'currentPage' => $currentPage,
			'lastPage' => self::$lastPage
		] );
		$pagination = new Pagination( $paginator,  self::$pagesPerFrame );
		
		$this->assertEquals( $expectedPageNumber, $pagination->NextFrame( ) );
	}
	
	public static function startingPageNumberProvider( ) : array {
		return [ [
			1, 1
		], [
			10, 1
		], [
			15, 1
		], [
			16, 16
		], [
			20, 16
		], [
			30, 16
		], [
			31, 31
		] ];
	}
	
	#[ DataProvider( 'startingPageNumberProvider' ) ]
	public function test_starting_page_number( int $currentPage, int $expectedPageNumber ) : void {
		$paginator = $this->createConfiguredStub( LengthAwarePaginator::class, [
			'currentPage' => $currentPage
		] );
		$pagination = new Pagination( $paginator,  self::$pagesPerFrame );
		
		$this->assertEquals( $expectedPageNumber, $pagination->StartingPageNumber( ) );
	}
	
	public static function endingPageNumberProvider( ) : array {
		return [ [
			1, 15
		], [
			10, 15
		], [
			15, 15
		], [
			16, 30
		], [
			20, 30
		], [
			30, 30
		], [
			31, 45
		], [
			self::$lastPage - 1, self::$lastPage
		], [
			self::$lastPage, self::$lastPage
		] ];
	}
	
	#[ DataProvider( 'endingPageNumberProvider' ) ]
	public function test_ending_page_number( int $currentPage, int $expectedPageNumber ) : void {
		$paginator = $this->createConfiguredStub( LengthAwarePaginator::class, [
			'currentPage' => $currentPage,
			'lastPage' => self::$lastPage
		] );
		$pagination = new Pagination( $paginator,  self::$pagesPerFrame );
		
		$this->assertEquals( $expectedPageNumber, $pagination->EndingPageNumber( ) );
	}
}

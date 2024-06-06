<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Pagination\LengthAwarePaginator;

class Pagination extends Component {
	public LengthAwarePaginator $paginator;
	public int $pagesPerFrame;
	
	/**
	 * Create a new component instance.
	 */
	public function __construct( LengthAwarePaginator $paginator, int $pagesPerFrame = 15 ) {
		$this->paginator = $paginator;
		$this->pagesPerFrame = $pagesPerFrame;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render( ) : View|Closure|string {
		return view( 'components.pagination' );
	}
	
	public function PrevFrame( ) : int {
		$currentPage = $this->paginator->currentPage( );
		$frameNumber = ( int ) ceil( $currentPage / $this->pagesPerFrame );
		
		return ( $frameNumber - 1 ) * $this->pagesPerFrame;
	}
	
	public function NextFrame( ) : int {
		$currentPage = $this->paginator->currentPage( );
		$frameNumber = ( int ) ceil( $currentPage / $this->pagesPerFrame );
		$pageNumber = $frameNumber * $this->pagesPerFrame + 1;
		
		return ( $pageNumber < $this->paginator->lastPage( ) ) ? $pageNumber : 0;
	}
	
	public function StartingPageNumber( ) : int {
		$currentPage = $this->paginator->currentPage( );
		$frameNumber = ( int ) ceil( $currentPage / $this->pagesPerFrame );
		
		return ( $frameNumber - 1 ) * $this->pagesPerFrame + 1;
	}
	
	public function EndingPageNumber( ) : int {
		$currentPage = $this->paginator->currentPage( );
		$frameNumber = ( int ) ceil( $currentPage / $this->pagesPerFrame );
		$pageNumber = $frameNumber * $this->pagesPerFrame;
		
		return ( $pageNumber < $this->paginator->lastPage( ) ) ? $pageNumber : $this->paginator->lastPage( );
	}
}

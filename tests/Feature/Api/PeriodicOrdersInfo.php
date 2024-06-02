<?php
namespace Tests\Feature\Api;

use Illuminate\Database\Eloquent\Collection;

class PeriodicOrdersInfo {
	public \DateTime $periodFrom;
	public \DateTime $periodTo;
	public \DateTime $pastPeriodFrom;
	public \DateTime $pastPeriodTo;
	public \DateTime $futurePeriodFrom;
	public \DateTime $futurePeriodTo;
	
	/**
	 * Заявки до начала периода
	 */
	public Collection $pastOrders;
	
	/**
	 * Заявки после начала периода
	 */
	public Collection $futureOrders;
	
	/**
	 * Заявки, которые оканчиваются внутри периода
	 */
	public Collection $endInPeriodOrders;
	
	/**
	 * Заявки, которые начинаются внутри периода
	 */
	public Collection $beginInPeriodOrders;
	
	/**
	 * Заявки, которые полностью входят в период
	 */
	public Collection $insidePeriodOrders;
	
	/**
	 * Заявки, которые охватывают период
	 */
	public Collection $coverPeriodOrders;
}

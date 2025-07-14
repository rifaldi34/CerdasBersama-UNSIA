<?php 

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class M_Api extends Model
{

	public function builderToPagination(
		BaseBuilder $baseBuilder,
		int $limit,
		int $offset,
		?array $filterArrAnd = null,
		?array $filterArrOr  = null,
		?array $orderByArr   = null
	): array	
	{
		$countBuilder = $this->db->newQuery()->fromSubQuery($baseBuilder, 'sub');
	
		// AND‐filters
		if (! empty($filterArrAnd)) {
			foreach ($filterArrAnd as $col => $val) {
				$countBuilder->like($col, $val, 'both');
			}
		}
		// OR‐filters
		if (! empty($filterArrOr)) {
			$countBuilder->groupStart();
			foreach ($filterArrOr as $col => $val) {
				$countBuilder->orLike($col, $val, 'both');
			}
			$countBuilder->groupEnd();
		}
	
		$totalRows = $countBuilder->countAllResults();
	
		//
		// 2) Build and run the “data” query
		//
		$dataBuilder = $this->db->newQuery()->fromSubQuery($baseBuilder, 'sub');
	
		// Re‐apply filters to the data query
		if (! empty($filterArrAnd)) {
			foreach ($filterArrAnd as $col => $val) {
				$dataBuilder->like($col, $val, 'both');
			}
		}
		if (! empty($filterArrOr)) {
			$dataBuilder->groupStart();
			foreach ($filterArrOr as $col => $val) {
				$dataBuilder->orLike($col, $val, 'both');
			}
			$dataBuilder->groupEnd();
		}
	
		// Ordering
		if (! empty($orderByArr)) {
			foreach ($orderByArr as $col => $dir) {
				$dataBuilder->orderBy($col, $dir);
			}
		}
	
		// Pagination
		$resultSet = $dataBuilder
			->limit($limit, $offset)
			->get();
	
		return [
			'count_all'      => $totalRows,
			'data_current'   => $resultSet,
			'current_limit'  => $limit,
			'current_offset' => $offset,
		];
	}
	
}
<?php

namespace Charcoal\Admin\Widget\Search;

use PDO;

// From 'charcoal-search'
use Charcoal\Search\SearchLog;

/**
 * Search widget to show the latest searched-for terms that produced no results.
 */
class NoResultsSearchWidget extends AbstractSearchHistoryWidget
{
    /**
     * Load the top searches, from the source, that produced no results.
     *
     * @return SearchLog[]
     */
    public function loadSearchHistory()
    {
        $proto  = $this->modelFactory()->get(SearchLog::class);
        $source = $proto->source();
        $table  = $source->table();

        $q = strtr('
        SELECT
            `keyword`,
            COUNT(`keyword`) as num_searches,
            SUM(`num_results`) as num_results,
            MAX(ts) as ts
        FROM
            `%table`
        WHERE
            `num_results` = 0
        AND
            `ts` > :start
        AND
            `ts` <= :end
        GROUP BY
            `keyword`
        ORDER BY
            num_searches DESC,
            num_results DESC
        LIMIT
            20
        ', [
            '%table' => $table
        ]);

        $sth = $source->dbQuery($q, [
            'start' => $this->startDate()->format('Y-m-d H:i:s'),
            'end'   => $this->endDate()->format('Y-m-d H:i:s')
        ]);

        $results = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }
}

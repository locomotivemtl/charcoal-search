<?php

namespace Charcoal\Admin\Widget\Search;

use \PDO;

use \Charcoal\Search\SearchLog;

/**
 * Search widget to show the latest searched-for terms and their results.
 */
class TopSearchWidget extends AbstractSearchHistoryWidget
{
    /**
     * Load the top searches from the source.
     *
     * @return SearchLog[]
     */
    public function loadSearchHistory()
    {
        $proto  = $this->modelFactory()->get(SearchLog::class);
        $source = $proto->source();
        $table  = $source->table();

        $q = '
        SELECT
            `keyword`,
            COUNT(`keyword`) as num_searches,
            num_results,
            ts
        FROM
            `'.$table.'`
        WHERE
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
        ';

        $sth = $source->dbQuery($q, [
            'start' => $this->startDate()->format('Y-m-d H:i:s'),
            'end'   => $this->endDate()->format('Y-m-d H:i:s')
        ]);

        $results = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }
}

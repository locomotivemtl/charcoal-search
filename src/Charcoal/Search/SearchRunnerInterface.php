<?php

namespace Charcoal\Search;

interface SearchRunnerInterface
{
    /**
     * @return \Charcoal\Search\SearchConfig
     */
    public function searchConfig();

    /**
     * @return \Charcoal\Search\SearchLog
     */
    public function searchLog();

    /**
     * @return array
     */
    public function results();

    /**
     * @param string $keyword The searched keyword.
     * @return array
     */
    public function search($keyword);
}

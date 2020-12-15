<?php

namespace App\Service\Fetch;

interface FetchInterface {
  function get(string $path): Array;
}
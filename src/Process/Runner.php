<?php

namespace Saeedvir\Modules\Process;

use Saeedvir\Modules\Contracts\RepositoryInterface;
use Saeedvir\Modules\Contracts\RunableInterface;

class Runner implements RunableInterface
{
    /**
     * The module instance.
     */
    protected RepositoryInterface $module;

    public function __construct(RepositoryInterface $module)
    {
        $this->module = $module;
    }

    /**
     * Run the given command.
     */
    public function run(string $command)
    {
        passthru($command);
    }
}

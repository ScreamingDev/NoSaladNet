<?php

/**
 * Show progress information on current STDOUT.
 *
 * Currently only linear calculation of estimated time and
 * progress is preserved.
 *
 * ## Examples
 *
 * ### Full format example
 *
 * You can get plenty of information to show
 *
 *     $progress             = new \Helper\ProgressInfo(
 *         "\rImported :tasksDone of :tasksTotal rows (:percentage%, ~:estimatedTime left)"
 *     );
 *     $progress->tasksTotal = $someSource->countRows();
 *
 *     while ($row = $someSource->getRow()) {
 *         $someImport->handles($row);
 *
 *         $progress->step();                // one more done - yeha!
 *         $progress->printPerPercent(5);    // show it on every 5th percent tick
 *     }
 *
 */
class ProgressInfo
{
    public $tasksTotal = 0;
    public $tasksDone  = 0;
    public $format;

    /**
     * @var \DateTime
     */
    protected $_linearDateTime;
    protected $_startTime;

    protected $_timeSet = array();

    public function __construct($outputFormat = ':linearPercentage%')
    {
        $this->format = $outputFormat;
        $this->reset();
    }

    public function __toString()
    {
        $replace_pairs = array(
            ':tasksDone'     => $this->tasksDone,
            ':tasksTotal'    => $this->tasksTotal,
            ':percentage'    => (int) ($this->getLinearPercentage() * 100),
            ':estimatedTime' => $this->getLinearEstimatedTime()->format('H:i:s')
        );

        return strtr($this->format, $replace_pairs);
    }

    public function startTask()
    {
        $this->_startTime = microtime(true);
    }

    public function getTimeTaken()
    {
        return $this->getCurrentTime() - $this->getStartTime();
    }

    public function printPerPercent($step = 1)
    {
        static $percentage = -1;

        $currentPercentage = $this->getLinearPercentage() * 100;
        if ($percentage + $step <= $currentPercentage)
        {
            $percentage = $currentPercentage;
            echo $this;
        }
    }

    public function printPerTasks($step = 1)
    {
        if ($this->tasksDone % $step == 0)
        {
            echo $this;
        }
    }

    /**
     * @return \DateTime seconds to go
     */
    public function getLinearEstimatedTime()
    {
        if (!$this->_linearDateTime)
        {
            $this->_linearDateTime = new \DateTime();
            $this->_linearDateTime->setTime(0, 0, 0);
        }

        $timeTaken = $this->getTimeTaken();

        if ($this->getLinearPercentage() != 0)
        {
            $timeLeft = ($timeTaken / $this->getLinearPercentage()) - $timeTaken;
        }
        else
        {
            $timeLeft = 0;
        }


        $hours = (int) ($timeLeft / 3600);
        $timeLeft %= 3600;

        $minutes = (int) ($timeLeft / 60);
        $timeLeft %= 60;

        $this->_linearDateTime->setTime($hours, $minutes, $timeLeft);

        return $this->_linearDateTime;
    }

    public function getCurrentTime()
    {
        return microtime(true);
    }

    public function step($load = 1)
    {
        $last = end($this->_timeSet);

        $time             = $this->getCurrentTime();
        $this->_timeSet[] = array(
            'load'  => $load,
            'time'  => $time,
            'taken' => $last['time'] - $time,
        );

        $this->tasksDone++;
    }

    /**
     * @return float
     */
    public function getLinearPercentage()
    {
        if ($this->tasksTotal == 0)
        {
            return 1;
        }

        return $this->tasksDone / $this->tasksTotal;
    }

    /**
     * @return mixed
     */
    private function getStartTime()
    {
        return $this->_startTime;
    }

    public function reset()
    {
        $this->startTask();

        $this->_timeSet = array(
            array(
                'load'  => -1,
                'time'  => $this->getStartTime(),
                'taken' => 0,
            )
        );
    }
}

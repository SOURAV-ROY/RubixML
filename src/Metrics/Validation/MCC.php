<?php

namespace Rubix\ML\Metrics\Validation;

use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Classifiers\Classifier;

class MCC implements Classification
{
    /**
     * Return a tuple of the min and max output value for this metric.
     *
     * @return array
     */
    public function range() : array
    {
        return [-1, 1];
    }

    /**
     * Score the Matthews correlation coefficient of the predicted classes.
     * Score is a number between -1 and 1.
     *
     * @param  \Rubix\ML\Classifiers\Classifier  $estimator
     * @param  \Runix\Engine\Datasets\Labeled  $testing
     * @return float
     */
    public function score(Classifier $estimator, Labeled $testing) : float
    {
        $predictions = $estimator->predict($testing);

        $labels = $testing->labels();

        $classes = array_unique(array_merge($predictions, $labels));

        $truePositives = $trueNegatives = $falsePositives
            = $falseNegatives = [];

        foreach ($classes as $class) {
            $truePositives[$class] = $trueNegatives[$class]
                = $falsePositives[$class] = $falseNegatives[$class] = 0;
        }

        foreach ($predictions as $i => $outcome) {
            if ($outcome === $labels[$i]) {
                $truePositives[$outcome]++;

                foreach ($classes as $class) {
                    if ($class !== $outcome) {
                        $trueNegatives[$class]++;
                    }
                }
            } else {
                $falsePositives[$outcome]++;
                $falseNegatives[$labels[$i]]++;
            }
        }

        $score = 0.0;

        foreach ($truePositives as $class => $tp) {
            $tn = $trueNegatives[$class];
            $fp = $falsePositives[$class];
            $fn = $falseNegatives[$class];

            $score += (($tp * $tn - $fp * $fn)
                / (sqrt(($tp + $fp) * ($tp + $fn) * ($tn + $fp) * ($tn + $fn))
                + self::EPSILON));
        }

        return $score / (count($classes) + self::EPSILON);
    }
}

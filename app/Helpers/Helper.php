<?php // Code within app\Helpers\Helper.php
if (! function_exists('totalAverageReviews')) {
    function totalAverageReviews(array $reviews)
        {
            $sumavg=$totalavg=0;
            foreach($reviews as $review) 
            {
                $avg = number_format(($review->avgrate),1) ;
                $sumavg =  $sumavg  + $avg;
            } 

            if($sumavg){
                $totalavg= number_format(($sumavg / count($reviews)),1);
            }
            return $totalavg;
        }

}

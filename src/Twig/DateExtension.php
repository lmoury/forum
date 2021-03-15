<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class DateExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('date_passed', [$this, 'datePasserSansHeure']),
            new TwigFunction('date_chatbox', [$this, 'dateChatbox']),
        ];
    }

    public function datePasserSansHeure($date)
    {
        setlocale(LC_TIME, 'fr_FR.utf8','fra');
        if(!ctype_digit($date)) {
    		$date = strtotime($date);
    	}
    	if(date('Ymd', $date) == date('Ymd')) {
    		$diff=(time())-$date;
    		if($diff<60) {
    			return 'Il y a '.$diff.' sec';
    		}
    		else if($diff < 3600) {
    			return 'Il y a '.round($diff/60, 0).' min';
    		}
    		else if($diff<10800) {
    			return 'Il y a '.round($diff/3600, 0).' heures';
    		}
    		else {
    			return 'Aujourd\'hui à '.date('H:i', $date);
    		}
    	}
    	else if(date('Ymd', $date) == date('Ymd', strtotime('- 1 DAY'))) {
    		return 'Hier à '.date('H:i', $date);
    	}
    	else {
    		$dateFR = strftime('%e ',$date);
    	    $dateFR .= ucfirst(strftime('%b %Y',$date));
    		return $dateFR;
    	}
    }

    public function dateChatbox($date)
    {
        setlocale(LC_TIME, 'fr_FR.utf8','fra');
        if(!ctype_digit($date)) {
    		$date = strtotime($date);
    	}
    	if(date('Ymd', $date) == date('Ymd')) {
    		return 'Aujourd\'hui à '.date('H:i', $date);
    	}
    	else if(date('Ymd', $date) == date('Ymd', strtotime('- 1 DAY'))) {
    		return 'Hier à '.date('H:i', $date);
    	}
        else if(date('Ymd', $date) > date('Ymd', strtotime('- 7 DAY'))) {
            $dateFR = ucfirst(strftime('%A',$date));
            $dateFR .= ' à '.date('H:i', $date);
            return $dateFR;
        }
    	else {
    		$dateFR = strftime('%e ',$date);
    	    $dateFR .= ucfirst(strftime('%b %Y',$date));
    		return $dateFR;
    	}
    }




    public function doSomething($value)
    {
        // ...
    }
}

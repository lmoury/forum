<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;


class ParserJBBCodeExtension extends AbstractExtension
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
            new TwigFunction('parser', [$this, 'parserJBBCode']),
            new TwigFunction('delParser', [$this, 'deleteJBBCode']),
        ];
    }

    public function parserJBBCode($text)
    {
        // BBcode array
      	$find = array(
            '~\[br]~s',
      		'~\[b\](.*?)\[/b\]~s',
      		'~\[i\](.*?)\[/i\]~s',
      		'~\[u\](.*?)\[/u\]~s',
            '~\[s\](.*?)\[/s\]~s',
            '~\[left\](.*?)\[/left\]~s',
            '~\[center\](.*?)\[/center\]~s',
            '~\[right\](.*?)\[/right\]~s',
      		'~\[quote\](.*?)\[/quote\]~s',
      		'~\[size=(.*?)\](.*?)\[/size\]~s',
            '~\[font=(.*?)\](.*?)\[/font\]~s',
      		'~\[color=(.*?)\](.*?)\[/color\]~s',
      		'~\[url=((?:ftp|https?)://.*?)\](.*?)\[/url\]~s',
      		'~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s',
            '~\[code\](.*?)\[/code\]~s',
            '~\[list\](.*?)\[/list\]~s',
            '~\[ol\](.*?)\[/ol\]~s',
            '~\[li\](.*?)\[/li\]~s',
            '~\[table\](.*?)\[/table\]~s',
            '~\[tr\](.*?)\[/tr\]~s',
            '~\[td\](.*?)\[/td\]~s',
            '~\:abruti:~s',
            '~\:boulet:~s',
            '~\:clin:~s',
            '~\lol~s',
            '~\:modo10:~s',
            '~\:ninja:~s',
            '~\:smile1:~s',
            '~\:troll:~s',
            '~\:troll1:~s',
            '~\:infectionpc:~s',
            '~\:matrix:~s',
            '~\:mdr1:~s',
            '~\:hacker:~s',
            '~\:virusdown:~s',
            '~\:anonymous:~s',
            '~\:nettoyage:~s',
      	);
      	// HTML tags to replace BBcode
      	$replace = array(
            '<br/>',
      		'<b>$1</b>',
      		'<i>$1</i>',
      		'<span style="text-decoration:underline;">$1</span>',
            '<strike>$1</strike>',
            '<div style="text-align:left;">$1</div>',
            '<div style="text-align:center;">$1</div>',
            '<div style="text-align:right;">$1</div>',
      		'<pre>$1</'.'pre>',
      		'<span style="font-size:$1px;">$2</span>',
            '<span style="font-family:$1px;">$2</span>',
      		'<span style="color:$1;">$2</span>',
      		'<a href="$1">$1</a>',
      		'<img src="$1" alt="" />',
            '<code>$1</code>',
            '<ul>$1</ul>',
            '<ol>$1</ol>',
            '<li>$1</li>',
            '<table class="table table-responsive">$1</table>',
            '<tr>$1</tr>',
            '<td>$1</td>',
            '<img src="/assets/img/smileys/abruti.gif" alt=":abruti:" />',
            '<img src="/assets/img/smileys/boulet.gif" alt=":boulet:" />',
            '<img src="/assets/img/smileys/clin.gif" alt=":clin:" />',
            '<img src="/assets/img/smileys/lol.gif" alt="lol" />',
            '<img src="/assets/img/smileys/modo10.gif" alt=":modo10:" />',
            '<img src="/assets/img/smileys/ninja.gif" alt=":ninja:" />',
            '<img src="/assets/img/smileys/simley.png" alt=":smile1:" />',
            '<img src="/assets/img/smileys/troll/1.png" alt=":troll:" />',
            '<img src="/assets/img/smileys/troll/troll.png" alt=":troll1:" />',
            '<img src="/assets/img/smileys/informatique/infect10.gif" alt=":infectionpc:" />',
            '<img src="/assets/img/smileys/informatique/smiley10.gif" alt=":matrix:" />',
            '<img src="/assets/img/smileys/informatique/mdr1.gif" alt=":mdr1:" />',
            '<img src="/assets/img/smileys/informatique/hacker10.gif" alt=":hacker:" />',
            '<img src="/assets/img/smileys/informatique/virusdown.gif" alt=":virusdown:" />',
            '<img src="/assets/img/smileys/informatique/anonymous.png" alt=":anonymous:" />',
            '<img src="/assets/img/smileys/nettoyage.gif" alt=":nettoyage:" />',
      	);
      	// Replacing the BBcodes with corresponding HTML tags
      	return preg_replace($find,$replace,$text);
    }

    public function deleteJBBCode($value)
    {
        return preg_replace('/\[(.*?)\]/ism','', $value) ;
    }

    public function doSomething($value)
    {
    }
}

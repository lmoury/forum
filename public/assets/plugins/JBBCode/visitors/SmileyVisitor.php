<?php

namespace JBBCode\visitors;

/**
 * This visitor is an example of how to implement smiley parsing on the JBBCode
 * parse graph. It converts :) into image tags pointing to /smiley.png.
 *
 * @author jbowens
 * @since April 2013
 */
class SmileyVisitor implements \JBBCode\NodeVisitor
{

    function visitDocumentElement(\JBBCode\DocumentElement $documentElement)
    {
        foreach($documentElement->getChildren() as $child) {
            $child->accept($this);
        }
    }

    function visitTextNode(\JBBCode\TextNode $textNode)
    {
        /* Convert :) into an image tag. */
        $textNode->setValue(str_replace(':boulet:', 
                                        '<img src="assets/img/smileys/boulet.gif" alt=":boulet:" />', 
                                        $textNode->getValue()));
        $textNode->setValue(str_replace(':abruti:', 
                                        '<img src="assets/img/smileys/abruti.gif" alt=":abruti:" />', 
                                        $textNode->getValue()));
        $textNode->setValue(str_replace(':clin:', 
                                        '<img src="assets/img/smileys/clin.gif" alt=":clin:" />', 
                                        $textNode->getValue()));
        $textNode->setValue(str_replace('lol', 
                                        '<img src="assets/img/smileys/lol.gif" alt="lol" />', 
                                        $textNode->getValue()));
        $textNode->setValue(str_replace(':modo10:', 
                                        '<img src="assets/img/smileys/modo10.gif" alt=":modo10:" />', 
                                        $textNode->getValue()));
        $textNode->setValue(str_replace(':ninja:', 
                                        '<img src="assets/img/smileys/ninja.gif" alt=":ninja:" />', 
                                        $textNode->getValue()));
        $textNode->setValue(str_replace(':smile1:', 
                                        '<img src="assets/img/smileys/simley.png" alt=":simle1:" />', 
                                        $textNode->getValue()));
        $textNode->setValue(str_replace(':troll:', 
                                        '<img src="assets/img/smileys/troll/1.png" alt=":troll:" />', 
                                        $textNode->getValue()));
        $textNode->setValue(str_replace(':troll1:', 
                                        '<img src="assets/img/smileys/troll/troll.png" alt=":troll1:" />', 
                                        $textNode->getValue()));
        $textNode->setValue(str_replace(':infectionpc:', 
                                        '<img src="assets/img/smileys/informatique/infect10.gif" alt=":infectionpc:" />', 
                                        $textNode->getValue()));
        $textNode->setValue(str_replace(':matrix:', 
                                        '<img src="assets/img/smileys/informatique/smiley10.gif" alt=":matrix:" />', 
                                        $textNode->getValue()));
        $textNode->setValue(str_replace(':mdr1:', 
                                        '<img src="assets/img/smileys/informatique/mdr1.gif" alt=":mdr1:" />', 
                                        $textNode->getValue()));
        $textNode->setValue(str_replace(':hacker:', 
                                        '<img src="assets/img/smileys/informatique/hacker10.gif" alt=":hacker:" />', 
                                        $textNode->getValue()));
        $textNode->setValue(str_replace(':virusdown:', 
                                        '<img src="assets/img/smileys/informatique/virusdown.gif" alt=":virusdown:" />', 
                                        $textNode->getValue()));
        $textNode->setValue(str_replace(':anonymous:', 
                                        '<img src="assets/img/smileys/informatique/anonymous.png" alt=":anonymous:" />', 
                                        $textNode->getValue()));
    }

    function visitElementNode(\JBBCode\ElementNode $elementNode)
    {
        /* We only want to visit text nodes within elements if the element's
         * code definition allows for its content to be parsed.
         */
        if ($elementNode->getCodeDefinition()->parseContent()) {
            foreach ($elementNode->getChildren() as $child) {
                $child->accept($this);
            }
        }
    }

}

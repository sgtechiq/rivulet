<?php

function article_slug($name)
{
    return str_replace(' ', '-', strtolower($name));
}

<?php
// Photos below are decorative examples, not evidence of a reported incident.
function wildlife_photo_for(string $description): string
{
    $text = strtolower($description);
    if (str_contains($text, 'elephant')) return 'elephant-forest.webp';
    if (str_contains($text, 'lion')) return 'lion.webp';
    if (str_contains($text, 'giraffe')) return 'giraffes.webp';
    if (str_contains($text, 'zebra')) return 'zebras.webp';
    if (str_contains($text, 'antelope')) return 'antelope.webp';
    if (str_contains($text, 'livestock')) return 'wildebeest.webp';
    if (str_contains($text, 'crop')) return 'elephant-hero.webp';
    return 'savannah.webp';
}
function status_class(string $status): string
{
    return match ($status) { 'Resolved'=>'resolved', 'In Progress'=>'progress', default=>'pending' };
}

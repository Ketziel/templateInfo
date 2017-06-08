<?php
$t =  $_GET['template'];
$output = '';

if (empty($t)){
    $templateInfo = array();
    $templates = $modx->getCollection('modTemplate',array());
    $resources = $modx->getIterator('modResource', array());

    foreach ($templates as $idx => $item) {
        $templateInfo[$idx] = array('name' => $item->get('templatename'),'published' => 0, 'unpublished' => 0);
    }

    foreach ($resources as $idx => $item) {
        $template = $item->get('template');
        if ($item->get('published') == 1){
            $templateInfo[$template]['published'] = $templateInfo[$template]['published']+1;
        } else {
            $templateInfo[$template]['unpublished'] = $templateInfo[$template]['unpublished']+1;
        }
    }
    
    $output .= '<table>';
    $output .= '<tr><th>ID</th><th>Name</th><th>Published</th><th>Unpublished</th><th>Total</th></tr>';
    foreach ($templateInfo as $idx => $template) {
        $total = $template['published'] + $template['unpublished'];
        $output .= '<tr'.(($total == 0) ? ' style="background-color: #DD4E42; color: #fefefe;"' : '').'>';
        $output .= '<td>'.$idx.'</td>';
        $output .= '<td>'.$template['name'].'</td>';
        $output .= '<td>'.$template['published'].'</td>';
        $output .= '<td>'.$template['unpublished'].'</td>';
        $output .= '<td>'.$total.'</td>';
        $output .= '<td>'.(($total > 0) ? '<a href="'.$modx->makeUrl($modx->resource->get('id')).'?template='.$idx.'">View</a>' : '').'</td>';
        $output .= '</tr>';
    }
    $output .= '</table>';

    
} else {
    $resources = $modx->getCollection('modResource',array('template'=>$t));
    $output .= '<table>';
    $output .= '<tr><th>ID</th><th>Name</th><th>Published</th><th>Created By</th></tr>';
    foreach ($resources as $idx => $item) {
        $profile = $modx->getObject('modUserProfile', array('internalKey' => $item->get('createdby')));
        //var_dump($profile);
        if (!empty($profile)){
            $fullname = $profile->get('fullname');
        } else {
            $fullname = "User not Found";
        }
        $output .= '<tr>';
        $output .= '<td>'.$idx.'</td>';
        $output .= '<td>'.$item->get('pagetitle').'</td>';
        $output .= '<td>'.(($item->get('published') == 1)? 'Yes':'No').'</td>';
        $output .= '<td>'.$fullname.'</td>';
        $output .= '<td><a href="'.$modx->makeUrl($idx).'" target="_blank">View</a></td>';
        $output .= '</tr>';
    }
    $output .= '</table>';
    $output .= '<a href="'.$modx->makeUrl($modx->resource->get('id')).'" class="button">View Templates</a>';
    
}

echo $output;
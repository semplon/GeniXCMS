<?php
/**
* GeniXCMS - Content Management System
* 
* PHP Based Content Management System and Framework
*
* @package GeniXCMS
* @since 0.0.1 build date 20150202
* @version 0.0.8
* @link https://github.com/semplon/GeniXCMS
* @link http://genixcms.org
* @author Puguh Wijayanto (www.metalgenix.com)
* @copyright 2014-2016 Puguh Wijayanto
* @license http://www.opensource.org/licenses/mit-license.php MIT
*
*/

if (isset($_GET['token']) 
    && Token::isExist($_GET['token'])) {
    $token = TOKEN;
}else{
    $token = '';
}
($_GET['act'] == "edit")? $pagetitle = 'Edit': $pagetitle = 'New';
($_GET['act'] == "edit")? $act = "edit&id={$_GET['id']}&token=".$token: $act = "add";

if(isset($data['post']) ) {
    if (!isset($data['post']['error'])) {
        //print_r($data['post']);
        foreach ($data['post'] as $p) {
            # code...
            $title = $p->title;
            $content = $p->content;
            $date = $p->date;
            $status = $p->status;
            $cat = $p->cat;
            $tags = @$p->tags;
        }
        if($status == 1) {
            $pub = "SELECTED"; 
            $unpub = ""; 
        }elseif ($status == 0) {
            $pub = ""; 
            $unpub = "SELECTED";
        }
    }else{
        $title = "";
        $content = "";
        $date = "";
        $status = "";
        $cat = "";
        $pub = "";
        $unpub = "";
        $tags = "";
        $data['alertred'][] = $data['post']['error'];
    }
    
}else{
    $title = "";
    $content = "";
    $date = "";
    $status = "";
    $cat = "";
    $pub = "";
    $unpub = "";
    $tags = "";
}

if (isset($data['alertgreen'])) {
    # code...
    echo "<div class=\"alert alert-success\" >
    <button type=\"button\" class=\"close\" data-dismiss=\"alert\">
        <span aria-hidden=\"true\">&times;</span>
        <span class=\"sr-only\">Close</span>
    </button>
    ";
    foreach ($data['alertgreen'] as $alert) {
        # code...
        echo "$alert\n";
    }
    echo "</div>";
}

if (isset($data['alertred'])) {
    # code...
    echo "<div class=\"alert alert-danger\" >
    <button type=\"button\" class=\"close\" data-dismiss=\"alert\">
        <span aria-hidden=\"true\">&times;</span>
        <span class=\"sr-only\">Close</span>
    </button>";
    foreach ($data['alertred'] as $alert) {
        # code...
        echo "$alert\n";
    }
    echo "</div>";
}

    
?>
<form action="index.php?page=pages&act=<?=$act?>&token=<?=$_GET['token'];?>" method="post" role="form" class="">
<div class="row">
    <div class="col-md-12">
        <?=Hooks::run('admin_page_notif_action', $data);?>
    </div>
    <div class="col-md-12">
        <h1><i class="fa fa-file-o"></i> <?=$pagetitle;?> <?=PAGE;?> 
            <div class="pull-right">
                <button type="submit" name="submit" class="btn btn-success">
                    <span class="glyphicon glyphicon-ok"></span>
                    <?=SUBMIT;?>
                </button>

                <a href="index.php?page=pages" class="btn btn-danger">
                    <span class="glyphicon glyphicon-remove"></span>
                    <?=CANCEL;?>
                </a>
            </div>
        </h1>
        <hr />
    </div>
    <div class="col-sm-12">
        <div class="row">
            
                <div class="col-sm-8" id="myTab">
                    <?php
                if(Options::v('multilang_enable') === 'on') {
                    $def = Options::v('multilang_default');
                    $deflang = Language::getDefaultLang();
                    $listlang = json_decode(Options::v('multilang_country'), true);
                    $deflag = strtolower($listlang[$def]['flag']);

                    echo "
                    <ul class=\"nav nav-tabs\" role=\"tablist\">
                        <li class=\"active\"><a href=\"#lang-{$def}\" role=\"tab\" data-toggle=\"tab\"><span class=\"flag-icon flag-icon-{$deflag}\"></span> {$deflang['country']}</a></li>";
                    
                    unset($listlang[Options::v('multilang_default')]);
                    foreach ($listlang as $key => $value) {
                        $flag = strtolower($value['flag']);
                        echo "
                        <li><a href=\"#lang-{$key}\" role=\"tab\" data-toggle=\"tab\"><span class=\"flag-icon flag-icon-{$flag}\"></span> {$value['country']}</a></li>";
                    }
                    
                    echo "
                    </ul>
                    <div class=\"clearfix\">&nbsp;</div>
                    <div class=\"tab-content\">
                    <!-- Tab Pane General -->
                    <div class=\"tab-pane active\" id=\"lang-{$def}\">
                        <div class=\"form-group\">
                            <label for=\"title\">".TITLE." ({$def}) </label>
                            <input type=\"title\" name=\"title[{$def}]\" class=\"form-control\" id=\"title\" placeholder=\"Post Title\" value=\"{$title}\">
                        </div>
                        <div class=\"form-group\">
                            <label for=\"content\">".CONTENT."</label>
                            <textarea name=\"content[{$def}]\" class=\"form-control content editor\" id=\"content\" rows=\"20\">{$content}</textarea>
                        </div>
                    </div>
                    ";
                    unset($listlang[Options::v('multilang_default')]);
                    foreach ($listlang as $key => $value) {
                        if (isset($_GET['act']) && $_GET['act'] == 'edit') {
                            $lang = Language::getLangParam($key, $_GET['id']);
                            if ($lang == '') {
                                $lang['title'] = $title;
                                $lang['content'] = $content; 
                            }else{
                                $lang = $lang;
                            }
                        }else{
                            $lang['title'] = '';
                            $lang['content'] = ''; 
                        }
                        echo "
                    <div class=\"tab-pane\" id=\"lang-{$key}\">

                        <div class=\"form-group\">
                            <label for=\"title\">".TITLE." ({$key}) </label>
                            <input type=\"title\" name=\"title[{$key}]\" class=\"form-control\" id=\"title\" placeholder=\"Post Title\" value=\"{$lang['title']}\">
                        </div>
                        <div class=\"form-group\">
                            <label for=\"content\">".CONTENT."</label>
                            <textarea name=\"content[{$key}]\" class=\"form-control content editor\" id=\"content\" rows=\"20\">{$lang['content']}</textarea>
                        </div>
                    </div>

                        ";
                        unset($lang);

                    }

                    echo "</div>";

                }else{

                ?>
                    <div class="form-group">
                        <label for="title"><?=TITLE;?></label>
                        <input type="title" name="title" class="form-control" id="title" placeholder="Post Title" value="<?=$title;?>">
                    </div>
                    <div class="form-group">
                        <label for="content"><?=CONTENT;?></label>
                        <textarea name="content" class="form-control content editor" id="content" rows="20"><?=$content;?></textarea>
                    </div>
                <?php
                }
                ?>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?=OPTIONS;?></h3>
                        </div>
                        <div class="panel-body">

                            <div class="form-group">
                                <label><?=STATUS;?></label>
                                <select name="status" class="form-control">
                                    <option value="1" <?=$pub;?>><?=PUBLISH;?></option>
                                    <option value="0" <?=$unpub;?>><?=UNPUBLISH;?></option>
                                </select>
                                <small><?=PUBLISHED;?> or <?=UNPUBLISHED;?></small>
                            </div>

                            <div class="form-group">
                                <label><?=POST_DATE;?></label>
                                <input type="text" name="date" class="form-control" value="<?=$date;?>">
                                <small><?=LEFT_IT_BLANK_NOW_DATE;?></small>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?=TAGS;?></h3>
                        </div>
                        <div class="panel-body">
                            <textarea name="tags" class="form-control"><?=$tags;?></textarea>
                            <small><?=TAGS_DESC;?></small>
                        </div>
                    </div> -->
                </div>
            
        </div>
        
    </div>
</div>
<input type="hidden" name="token" value="<?=$token;?>">
</form>


<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>{$title}</title>
    <link rel="stylesheet" type="text/css" href="{$subdir}media/style.css" />
    <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'/>
  </head>
  <body>

    <table width="100%">
      <tr valign="top">
        <td width="195" class="menu">
          {if count($ric) >= 1}
            <div class="package">
              <div id="ric">
                {section name=ric loop=$ric}
                  <p><a href="{$subdir}{$ric[ric].file}">{$ric[ric].name}</a></p>
                {/section}
              </div>
            </div>
          {/if}
          {if $hastodos}
            <div class="package">
              <div id="todolist">
                <p><a href="{$subdir}{$todolink}">Todo List</a></p>
              </div>
            </div>
          {/if}
          <b>Packages:</b><br />
          <div class="package">
            <ul>
              {section name=packagelist loop=$packageindex}
                <li>
                  <a href="{$subdir}{$packageindex[packagelist].link}">{$packageindex[packagelist].title}</a>
                </li>
              {/section}
            </ul>
          </div>
          <br />
          {if $tutorials}
            <b>Tutorials/Manuals:</b><br />
            <div class="package">
              {if $tutorials.pkg}
                <strong>Package-level:</strong>
                {section name=ext loop=$tutorials.pkg}
                  {$tutorials.pkg[ext]}
                {/section}
              {/if}
              {if $tutorials.cls}
                <strong>Class-level:</strong>
                {section name=ext loop=$tutorials.cls}
                  {$tutorials.cls[ext]}
                {/section}
              {/if}
              {if $tutorials.proc}
                <strong>Procedural-level:</strong>
                {section name=ext loop=$tutorials.proc}
                  {$tutorials.proc[ext]}
                {/section}
              {/if}
            </div>
          {/if}
          {if !$noleftindex}{assign var="noleftindex" value=false}{/if}
          {if !$noleftindex}
            <br />
            {if $compiledinterfaceindex}
              <b>Interfaces:</b><br />
              {eval var=$compiledinterfaceindex}
            {/if}
            {if $compiledclassindex}
              <b>Classes:</b><br />
              {eval var=$compiledclassindex}
            {/if}
          {/if}
        </td>
        <td>
          <table style="width:750px;" cellpadding="10" cellspacing="10px" width="100%">
            <tr>
              <td valign="top">
                {if !$hasel}{assign var="hasel" value=false}{/if}
                {if $eltype == 'class' && $is_interface}
                  {assign var="eltype" value="interface"}
                {/if}
                {if $hasel}
                  <h1>{$package}{if $subpackage != ''}::{$subpackage}{/if}::{$class_name}</h1>
                {/if}
                <div class="menu">
          {assign var="packagehaselements" value=false}
          {foreach from=$packageindex item=thispackage}
            {if in_array($package, $thispackage)}
              {assign var="packagehaselements" value=true}
            {/if}
          {/foreach}
          {if $packagehaselements}
            [ <a href="{$subdir}classtrees_{$package}.html">class tree: {$package}</a> ]
            [ <a href="{$subdir}elementindex_{$package}.html">index: {$package}</a> ]
          {/if}
          [ <a href="{$subdir}elementindex.html">all elements</a> ]
                </div>
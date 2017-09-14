<!DOCTYPE html>
<html>
  <head><!--[if lt IE 9]><script language="javascript" type="text/javascript" src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <meta charset="UTF-8"><style>/*
 * Bootstrap v2.2.1
 *
 * Copyright 2012 Twitter, Inc
 * Licensed under the Apache License v2.0
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Designed and built with all the love in the world @twitter by @mdo and @fat.
 */
.clearfix {
  *zoom: 1;
}
.clearfix:before,
.clearfix:after {
  display: table;
  content: "";
  line-height: 0;
}
.clearfix:after {
  clear: both;
}
html {
  font-size: 100%;
  -webkit-text-size-adjust: 100%;
  -ms-text-size-adjust: 100%;
}
a:focus {
  outline: thin dotted #333;
  outline: 5px auto -webkit-focus-ring-color;
  outline-offset: -2px;
}
a:hover,
a:active {
  outline: 0;
}
button,
input,
select,
textarea {
  margin: 0;
  font-size: 100%;
  vertical-align: middle;
}
button,
input {
  *overflow: visible;
  line-height: normal;
}
button::-moz-focus-inner,
input::-moz-focus-inner {
  padding: 0;
  border: 0;
}
body {
  margin: 0;
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 14px;
  line-height: 20px;
  color: #333;
  background-color: #fff;
}
a {
  color: #08c;
  text-decoration: none;
}
a:hover {
  color: #005580;
  text-decoration: underline;
}
.row {
  margin-left: -20px;
  *zoom: 1;
}
.row:before,
.row:after {
  display: table;
  content: "";
  line-height: 0;
}
.row:after {
  clear: both;
}
[class*="span"] {
  float: left;
  min-height: 1px;
  margin-left: 20px;
}
.container,
.navbar-static-top .container,
.navbar-fixed-top .container,
.navbar-fixed-bottom .container {
  width: 940px;
}
.span12 {
  width: 940px;
}
.span11 {
  width: 860px;
}
.span10 {
  width: 780px;
}
.span9 {
  width: 700px;
}
.span8 {
  width: 620px;
}
.span7 {
  width: 540px;
}
.span6 {
  width: 460px;
}
.span5 {
  width: 380px;
}
.span4 {
  width: 300px;
}
.span3 {
  width: 220px;
}
.span2 {
  width: 140px;
}
.span1 {
  width: 60px;
}
[class*="span"].pull-right,
.row-fluid [class*="span"].pull-right {
  float: right;
}
.container {
  margin-right: auto;
  margin-left: auto;
  *zoom: 1;
}
.container:before,
.container:after {
  display: table;
  content: "";
  line-height: 0;
}
.container:after {
  clear: both;
}
p {
  margin: 0 0 10px;
}
.lead {
  margin-bottom: 20px;
  font-size: 21px;
  font-weight: 200;
  line-height: 30px;
}
small {
  font-size: 85%;
}
h1 {
  margin: 10px 0;
  font-family: inherit;
  font-weight: bold;
  line-height: 20px;
  color: inherit;
  text-rendering: optimizelegibility;
}
h1 small {
  font-weight: normal;
  line-height: 1;
  color: #999;
}
h1 {
  line-height: 40px;
}
h1 {
  font-size: 38.5px;
}
h1 small {
  font-size: 24.5px;
}
body {
  margin-top: 90px;
}
.header {
  position: fixed;
  top: 0;
  left: 50%;
  margin-left: -480px;
  background-color: #fff;
  border-bottom: 1px solid #ddd;
  padding-top: 10px;
  z-index: 10;
}
.footer {
  color: #ddd;
  font-size: 12px;
  text-align: center;
  margin-top: 20px;
}
.footer a {
  color: #ccc;
  text-decoration: underline;
}
.the-icons {
  font-size: 14px;
  line-height: 24px;
}
.switch {
  position: absolute;
  right: 0;
  bottom: 10px;
  color: #666;
}
.switch input {
  margin-right: 0.3em;
}
.codesOn .i-name {
  display: none;
}
.codesOn .i-code {
  display: inline;
}
.i-code {
  display: none;
}
@font-face {
      font-family: 'icon-font';
      src: url('../fonts/icon-font/icon-font.eot?96018075');
      src: url('../fonts/icon-font/icon-font.eot?96018075#iefix') format('embedded-opentype'),
           url('../fonts/icon-font/icon-font.woff?96018075') format('woff'),
           url('../fonts/icon-font/icon-font.ttf?96018075') format('truetype'),
           url('../fonts/icon-font/icon-font.svg?96018075#icon-font') format('svg');
      font-weight: normal;
      font-style: normal;
    }


    .demo-icon
    {
      font-family: "icon-font";
      font-style: normal;
      font-weight: normal;
      speak: none;

      display: inline-block;
      text-decoration: inherit;
      width: 1em;
      margin-right: .2em;
      text-align: center;
      /* opacity: .8; */

      /* For safety - reset parent styles, that can break glyph codes*/
      font-variant: normal;
      text-transform: none;

      /* fix buttons height, for twitter bootstrap */
      line-height: 1em;

      /* Animation center compensation - margins should be symmetric */
      /* remove if not needed */
      margin-left: .2em;

      /* You can be more comfortable with increased icons size */
      /* font-size: 120%; */

      /* Font smoothing. That was taken from TWBS */
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;

      /* Uncomment for 3D effect */
      /* text-shadow: 1px 1px 1px rgba(127, 127, 127, 0.3); */
    }
     </style>
    <link rel="stylesheet" href="css/animation.css"><!--[if IE 7]><link rel="stylesheet" href="css/icon-font-ie7.css"><![endif]-->
    <script>
      function toggleCodes(on) {
        var obj = document.getElementById('icons');

        if (on) {
          obj.className += ' codesOn';
        } else {
          obj.className = obj.className.replace(' codesOn', '');
        }
      }

    </script>
  </head>
  <body>
    <div class="container header">
      <h1>
        icon-font
         <small>font demo</small>
      </h1>
      <label class="switch">
        <input type="checkbox" onclick="toggleCodes(this.checked)">show codes
      </label>
    </div>
    <div id="icons" class="container">
      <div class="row">
        <div title="Code: 0xe800" class="the-icons span3"><i class="demo-icon icn-abacus">&#xe800;</i> <span class="i-name">icn-abacus</span><span class="i-code">0xe800</span></div>
        <div title="Code: 0xe801" class="the-icons span3"><i class="demo-icon icn-alarm">&#xe801;</i> <span class="i-name">icn-alarm</span><span class="i-code">0xe801</span></div>
        <div title="Code: 0xe802" class="the-icons span3"><i class="demo-icon icn-apartament">&#xe802;</i> <span class="i-name">icn-apartament</span><span class="i-code">0xe802</span></div>
        <div title="Code: 0xe803" class="the-icons span3"><i class="demo-icon icn-apps">&#xe803;</i> <span class="i-name">icn-apps</span><span class="i-code">0xe803</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe804" class="the-icons span3"><i class="demo-icon icn-arrow-circle-down">&#xe804;</i> <span class="i-name">icn-arrow-circle-down</span><span class="i-code">0xe804</span></div>
        <div title="Code: 0xe805" class="the-icons span3"><i class="demo-icon icn-arrow-circle-left">&#xe805;</i> <span class="i-name">icn-arrow-circle-left</span><span class="i-code">0xe805</span></div>
        <div title="Code: 0xe806" class="the-icons span3"><i class="demo-icon icn-arrow-circle-right">&#xe806;</i> <span class="i-name">icn-arrow-circle-right</span><span class="i-code">0xe806</span></div>
        <div title="Code: 0xe807" class="the-icons span3"><i class="demo-icon icn-arrow-circle-up">&#xe807;</i> <span class="i-name">icn-arrow-circle-up</span><span class="i-code">0xe807</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe808" class="the-icons span3"><i class="demo-icon icn-arrow-o-down">&#xe808;</i> <span class="i-name">icn-arrow-o-down</span><span class="i-code">0xe808</span></div>
        <div title="Code: 0xe809" class="the-icons span3"><i class="demo-icon icn-arrow-o-left">&#xe809;</i> <span class="i-name">icn-arrow-o-left</span><span class="i-code">0xe809</span></div>
        <div title="Code: 0xe80a" class="the-icons span3"><i class="demo-icon icn-arrow-o-right">&#xe80a;</i> <span class="i-name">icn-arrow-o-right</span><span class="i-code">0xe80a</span></div>
        <div title="Code: 0xe80b" class="the-icons span3"><i class="demo-icon icn-arrow-o-up">&#xe80b;</i> <span class="i-name">icn-arrow-o-up</span><span class="i-code">0xe80b</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe80c" class="the-icons span3"><i class="demo-icon icn-birthday">&#xe80c;</i> <span class="i-name">icn-birthday</span><span class="i-code">0xe80c</span></div>
        <div title="Code: 0xe80d" class="the-icons span3"><i class="demo-icon icn-block-page">&#xe80d;</i> <span class="i-name">icn-block-page</span><span class="i-code">0xe80d</span></div>
        <div title="Code: 0xe80e" class="the-icons span3"><i class="demo-icon icn-box-o">&#xe80e;</i> <span class="i-name">icn-box-o</span><span class="i-code">0xe80e</span></div>
        <div title="Code: 0xe80f" class="the-icons span3"><i class="demo-icon icn-box">&#xe80f;</i> <span class="i-name">icn-box</span><span class="i-code">0xe80f</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe810" class="the-icons span3"><i class="demo-icon icn-boxs">&#xe810;</i> <span class="i-name">icn-boxs</span><span class="i-code">0xe810</span></div>
        <div title="Code: 0xe811" class="the-icons span3"><i class="demo-icon icn-btn-off">&#xe811;</i> <span class="i-name">icn-btn-off</span><span class="i-code">0xe811</span></div>
        <div title="Code: 0xe812" class="the-icons span3"><i class="demo-icon icn-btn-on">&#xe812;</i> <span class="i-name">icn-btn-on</span><span class="i-code">0xe812</span></div>
        <div title="Code: 0xe813" class="the-icons span3"><i class="demo-icon icn-bubble">&#xe813;</i> <span class="i-name">icn-bubble</span><span class="i-code">0xe813</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe814" class="the-icons span3"><i class="demo-icon icn-calendar-day">&#xe814;</i> <span class="i-name">icn-calendar-day</span><span class="i-code">0xe814</span></div>
        <div title="Code: 0xe815" class="the-icons span3"><i class="demo-icon icn-calendar-ok">&#xe815;</i> <span class="i-name">icn-calendar-ok</span><span class="i-code">0xe815</span></div>
        <div title="Code: 0xe816" class="the-icons span3"><i class="demo-icon icn-calendar">&#xe816;</i> <span class="i-name">icn-calendar</span><span class="i-code">0xe816</span></div>
        <div title="Code: 0xe817" class="the-icons span3"><i class="demo-icon icn-cart">&#xe817;</i> <span class="i-name">icn-cart</span><span class="i-code">0xe817</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe818" class="the-icons span3"><i class="demo-icon icn-catalog">&#xe818;</i> <span class="i-name">icn-catalog</span><span class="i-code">0xe818</span></div>
        <div title="Code: 0xe819" class="the-icons span3"><i class="demo-icon icn-category-o">&#xe819;</i> <span class="i-name">icn-category-o</span><span class="i-code">0xe819</span></div>
        <div title="Code: 0xe81a" class="the-icons span3"><i class="demo-icon icn-category-v">&#xe81a;</i> <span class="i-name">icn-category-v</span><span class="i-code">0xe81a</span></div>
        <div title="Code: 0xe81b" class="the-icons span3"><i class="demo-icon icn-category">&#xe81b;</i> <span class="i-name">icn-category</span><span class="i-code">0xe81b</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe81c" class="the-icons span3"><i class="demo-icon icn-cc">&#xe81c;</i> <span class="i-name">icn-cc</span><span class="i-code">0xe81c</span></div>
        <div title="Code: 0xe81d" class="the-icons span3"><i class="demo-icon icn-checkmark-circle">&#xe81d;</i> <span class="i-name">icn-checkmark-circle</span><span class="i-code">0xe81d</span></div>
        <div title="Code: 0xe81e" class="the-icons span3"><i class="demo-icon icn-chevron-donw">&#xe81e;</i> <span class="i-name">icn-chevron-donw</span><span class="i-code">0xe81e</span></div>
        <div title="Code: 0xe81f" class="the-icons span3"><i class="demo-icon icn-chevron-left">&#xe81f;</i> <span class="i-name">icn-chevron-left</span><span class="i-code">0xe81f</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe820" class="the-icons span3"><i class="demo-icon icn-chevron-right">&#xe820;</i> <span class="i-name">icn-chevron-right</span><span class="i-code">0xe820</span></div>
        <div title="Code: 0xe821" class="the-icons span3"><i class="demo-icon icn-chevron-up">&#xe821;</i> <span class="i-name">icn-chevron-up</span><span class="i-code">0xe821</span></div>
        <div title="Code: 0xe822" class="the-icons span3"><i class="demo-icon icn-clock">&#xe822;</i> <span class="i-name">icn-clock</span><span class="i-code">0xe822</span></div>
        <div title="Code: 0xe823" class="the-icons span3"><i class="demo-icon icn-close">&#xe823;</i> <span class="i-name">icn-close</span><span class="i-code">0xe823</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe824" class="the-icons span3"><i class="demo-icon icn-code">&#xe824;</i> <span class="i-name">icn-code</span><span class="i-code">0xe824</span></div>
        <div title="Code: 0xe825" class="the-icons span3"><i class="demo-icon icn-comment">&#xe825;</i> <span class="i-name">icn-comment</span><span class="i-code">0xe825</span></div>
        <div title="Code: 0xe826" class="the-icons span3"><i class="demo-icon icn-conector">&#xe826;</i> <span class="i-name">icn-conector</span><span class="i-code">0xe826</span></div>
        <div title="Code: 0xe827" class="the-icons span3"><i class="demo-icon icn-conf">&#xe827;</i> <span class="i-name">icn-conf</span><span class="i-code">0xe827</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe828" class="the-icons span3"><i class="demo-icon icn-contact">&#xe828;</i> <span class="i-name">icn-contact</span><span class="i-code">0xe828</span></div>
        <div title="Code: 0xe829" class="the-icons span3"><i class="demo-icon icn-content-block">&#xe829;</i> <span class="i-name">icn-content-block</span><span class="i-code">0xe829</span></div>
        <div title="Code: 0xe82a" class="the-icons span3"><i class="demo-icon icn-content">&#xe82a;</i> <span class="i-name">icn-content</span><span class="i-code">0xe82a</span></div>
        <div title="Code: 0xe82b" class="the-icons span3"><i class="demo-icon icn-credential-o">&#xe82b;</i> <span class="i-name">icn-credential-o</span><span class="i-code">0xe82b</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe82c" class="the-icons span3"><i class="demo-icon icn-credential">&#xe82c;</i> <span class="i-name">icn-credential</span><span class="i-code">0xe82c</span></div>
        <div title="Code: 0xe82d" class="the-icons span3"><i class="demo-icon icn-crm">&#xe82d;</i> <span class="i-name">icn-crm</span><span class="i-code">0xe82d</span></div>
        <div title="Code: 0xe82e" class="the-icons span3"><i class="demo-icon icn-danger">&#xe82e;</i> <span class="i-name">icn-danger</span><span class="i-code">0xe82e</span></div>
        <div title="Code: 0xe82f" class="the-icons span3"><i class="demo-icon icn-division">&#xe82f;</i> <span class="i-name">icn-division</span><span class="i-code">0xe82f</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe830" class="the-icons span3"><i class="demo-icon icn-email">&#xe830;</i> <span class="i-name">icn-email</span><span class="i-code">0xe830</span></div>
        <div title="Code: 0xe831" class="the-icons span3"><i class="demo-icon icn-eye-close">&#xe831;</i> <span class="i-name">icn-eye-close</span><span class="i-code">0xe831</span></div>
        <div title="Code: 0xe832" class="the-icons span3"><i class="demo-icon icn-eye-open">&#xe832;</i> <span class="i-name">icn-eye-open</span><span class="i-code">0xe832</span></div>
        <div title="Code: 0xe833" class="the-icons span3"><i class="demo-icon icn-family">&#xe833;</i> <span class="i-name">icn-family</span><span class="i-code">0xe833</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe834" class="the-icons span3"><i class="demo-icon icn-folder-up">&#xe834;</i> <span class="i-name">icn-folder-up</span><span class="i-code">0xe834</span></div>
        <div title="Code: 0xe835" class="the-icons span3"><i class="demo-icon icn-folder">&#xe835;</i> <span class="i-name">icn-folder</span><span class="i-code">0xe835</span></div>
        <div title="Code: 0xe836" class="the-icons span3"><i class="demo-icon icn-group">&#xe836;</i> <span class="i-name">icn-group</span><span class="i-code">0xe836</span></div>
        <div title="Code: 0xe837" class="the-icons span3"><i class="demo-icon icn-home">&#xe837;</i> <span class="i-name">icn-home</span><span class="i-code">0xe837</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe838" class="the-icons span3"><i class="demo-icon icn-industry">&#xe838;</i> <span class="i-name">icn-industry</span><span class="i-code">0xe838</span></div>
        <div title="Code: 0xe839" class="the-icons span3"><i class="demo-icon icn-intranet">&#xe839;</i> <span class="i-name">icn-intranet</span><span class="i-code">0xe839</span></div>
        <div title="Code: 0xe83a" class="the-icons span3"><i class="demo-icon icn-inventary">&#xe83a;</i> <span class="i-name">icn-inventary</span><span class="i-code">0xe83a</span></div>
        <div title="Code: 0xe83b" class="the-icons span3"><i class="demo-icon icn-kardex">&#xe83b;</i> <span class="i-name">icn-kardex</span><span class="i-code">0xe83b</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe83c" class="the-icons span3"><i class="demo-icon icn-key">&#xe83c;</i> <span class="i-name">icn-key</span><span class="i-code">0xe83c</span></div>
        <div title="Code: 0xe83d" class="the-icons span3"><i class="demo-icon icn-level-page">&#xe83d;</i> <span class="i-name">icn-level-page</span><span class="i-code">0xe83d</span></div>
        <div title="Code: 0xe83e" class="the-icons span3"><i class="demo-icon icn-like-o">&#xe83e;</i> <span class="i-name">icn-like-o</span><span class="i-code">0xe83e</span></div>
        <div title="Code: 0xe83f" class="the-icons span3"><i class="demo-icon icn-like">&#xe83f;</i> <span class="i-name">icn-like</span><span class="i-code">0xe83f</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe840" class="the-icons span3"><i class="demo-icon icn-link">&#xe840;</i> <span class="i-name">icn-link</span><span class="i-code">0xe840</span></div>
        <div title="Code: 0xe841" class="the-icons span3"><i class="demo-icon icn-list">&#xe841;</i> <span class="i-name">icn-list</span><span class="i-code">0xe841</span></div>
        <div title="Code: 0xe842" class="the-icons span3"><i class="demo-icon icn-lock">&#xe842;</i> <span class="i-name">icn-lock</span><span class="i-code">0xe842</span></div>
        <div title="Code: 0xe843" class="the-icons span3"><i class="demo-icon icn-marca">&#xe843;</i> <span class="i-name">icn-marca</span><span class="i-code">0xe843</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe844" class="the-icons span3"><i class="demo-icon icn-media">&#xe844;</i> <span class="i-name">icn-media</span><span class="i-code">0xe844</span></div>
        <div title="Code: 0xe845" class="the-icons span3"><i class="demo-icon icn-move">&#xe845;</i> <span class="i-name">icn-move</span><span class="i-code">0xe845</span></div>
        <div title="Code: 0xe846" class="the-icons span3"><i class="demo-icon icn-movil">&#xe846;</i> <span class="i-name">icn-movil</span><span class="i-code">0xe846</span></div>
        <div title="Code: 0xe847" class="the-icons span3"><i class="demo-icon icn-newspaper">&#xe847;</i> <span class="i-name">icn-newspaper</span><span class="i-code">0xe847</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe848" class="the-icons span3"><i class="demo-icon icn-off">&#xe848;</i> <span class="i-name">icn-off</span><span class="i-code">0xe848</span></div>
        <div title="Code: 0xe849" class="the-icons span3"><i class="demo-icon icn-order-ok">&#xe849;</i> <span class="i-name">icn-order-ok</span><span class="i-code">0xe849</span></div>
        <div title="Code: 0xe84a" class="the-icons span3"><i class="demo-icon icn-order">&#xe84a;</i> <span class="i-name">icn-order</span><span class="i-code">0xe84a</span></div>
        <div title="Code: 0xe84b" class="the-icons span3"><i class="demo-icon icn-page">&#xe84b;</i> <span class="i-name">icn-page</span><span class="i-code">0xe84b</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe84c" class="the-icons span3"><i class="demo-icon icn-pencil">&#xe84c;</i> <span class="i-name">icn-pencil</span><span class="i-code">0xe84c</span></div>
        <div title="Code: 0xe84d" class="the-icons span3"><i class="demo-icon icn-phone">&#xe84d;</i> <span class="i-name">icn-phone</span><span class="i-code">0xe84d</span></div>
        <div title="Code: 0xe84e" class="the-icons span3"><i class="demo-icon icn-photo">&#xe84e;</i> <span class="i-name">icn-photo</span><span class="i-code">0xe84e</span></div>
        <div title="Code: 0xe84f" class="the-icons span3"><i class="demo-icon icn-picture">&#xe84f;</i> <span class="i-name">icn-picture</span><span class="i-code">0xe84f</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe850" class="the-icons span3"><i class="demo-icon icn-plus">&#xe850;</i> <span class="i-name">icn-plus</span><span class="i-code">0xe850</span></div>
        <div title="Code: 0xe851" class="the-icons span3"><i class="demo-icon icn-point-1">&#xe851;</i> <span class="i-name">icn-point-1</span><span class="i-code">0xe851</span></div>
        <div title="Code: 0xe852" class="the-icons span3"><i class="demo-icon icn-point-2">&#xe852;</i> <span class="i-name">icn-point-2</span><span class="i-code">0xe852</span></div>
        <div title="Code: 0xe853" class="the-icons span3"><i class="demo-icon icn-point-3">&#xe853;</i> <span class="i-name">icn-point-3</span><span class="i-code">0xe853</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe854" class="the-icons span3"><i class="demo-icon icn-point-4">&#xe854;</i> <span class="i-name">icn-point-4</span><span class="i-code">0xe854</span></div>
        <div title="Code: 0xe855" class="the-icons span3"><i class="demo-icon icn-point-menu">&#xe855;</i> <span class="i-name">icn-point-menu</span><span class="i-code">0xe855</span></div>
        <div title="Code: 0xe856" class="the-icons span3"><i class="demo-icon icn-reorder">&#xe856;</i> <span class="i-name">icn-reorder</span><span class="i-code">0xe856</span></div>
        <div title="Code: 0xe857" class="the-icons span3"><i class="demo-icon icn-rocket">&#xe857;</i> <span class="i-name">icn-rocket</span><span class="i-code">0xe857</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe858" class="the-icons span3"><i class="demo-icon icn-rrhh">&#xe858;</i> <span class="i-name">icn-rrhh</span><span class="i-code">0xe858</span></div>
        <div title="Code: 0xe859" class="the-icons span3"><i class="demo-icon icn-save">&#xe859;</i> <span class="i-name">icn-save</span><span class="i-code">0xe859</span></div>
        <div title="Code: 0xe85a" class="the-icons span3"><i class="demo-icon icn-search">&#xe85a;</i> <span class="i-name">icn-search</span><span class="i-code">0xe85a</span></div>
        <div title="Code: 0xe85b" class="the-icons span3"><i class="demo-icon icn-settings">&#xe85b;</i> <span class="i-name">icn-settings</span><span class="i-code">0xe85b</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe85c" class="the-icons span3"><i class="demo-icon icn-shop">&#xe85c;</i> <span class="i-name">icn-shop</span><span class="i-code">0xe85c</span></div>
        <div title="Code: 0xe85d" class="the-icons span3"><i class="demo-icon icn-shortcut">&#xe85d;</i> <span class="i-name">icn-shortcut</span><span class="i-code">0xe85d</span></div>
        <div title="Code: 0xe85e" class="the-icons span3"><i class="demo-icon icn-sort-asc">&#xe85e;</i> <span class="i-name">icn-sort-asc</span><span class="i-code">0xe85e</span></div>
        <div title="Code: 0xe85f" class="the-icons span3"><i class="demo-icon icn-sort-desc">&#xe85f;</i> <span class="i-name">icn-sort-desc</span><span class="i-code">0xe85f</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe860" class="the-icons span3"><i class="demo-icon icn-sort">&#xe860;</i> <span class="i-name">icn-sort</span><span class="i-code">0xe860</span></div>
        <div title="Code: 0xe861" class="the-icons span3"><i class="demo-icon icn-suitcase">&#xe861;</i> <span class="i-name">icn-suitcase</span><span class="i-code">0xe861</span></div>
        <div title="Code: 0xe862" class="the-icons span3"><i class="demo-icon icn-sync">&#xe862;</i> <span class="i-name">icn-sync</span><span class="i-code">0xe862</span></div>
        <div title="Code: 0xe863" class="the-icons span3"><i class="demo-icon icn-system">&#xe863;</i> <span class="i-name">icn-system</span><span class="i-code">0xe863</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe864" class="the-icons span3"><i class="demo-icon icn-tabs-mobile">&#xe864;</i> <span class="i-name">icn-tabs-mobile</span><span class="i-code">0xe864</span></div>
        <div title="Code: 0xe865" class="the-icons span3"><i class="demo-icon icn-tabs">&#xe865;</i> <span class="i-name">icn-tabs</span><span class="i-code">0xe865</span></div>
        <div title="Code: 0xe866" class="the-icons span3"><i class="demo-icon icn-trash">&#xe866;</i> <span class="i-name">icn-trash</span><span class="i-code">0xe866</span></div>
        <div title="Code: 0xe867" class="the-icons span3"><i class="demo-icon icn-user">&#xe867;</i> <span class="i-name">icn-user</span><span class="i-code">0xe867</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe868" class="the-icons span3"><i class="demo-icon icn-users">&#xe868;</i> <span class="i-name">icn-users</span><span class="i-code">0xe868</span></div>
        <div title="Code: 0xe869" class="the-icons span3"><i class="demo-icon icn-warning">&#xe869;</i> <span class="i-name">icn-warning</span><span class="i-code">0xe869</span></div>
        <div title="Code: 0xe86a" class="the-icons span3"><i class="demo-icon icn-zoom">&#xe86a;</i> <span class="i-name">icn-zoom</span><span class="i-code">0xe86a</span></div>
        <div title="Code: 0xe86b" class="the-icons span3"><i class="demo-icon icn-zundi-o">&#xe86b;</i> <span class="i-name">icn-zundi-o</span><span class="i-code">0xe86b</span></div>
      </div>
      <div class="row">
        <div title="Code: 0xe86c" class="the-icons span3"><i class="demo-icon icn-zundi">&#xe86c;</i> <span class="i-name">icn-zundi</span><span class="i-code">0xe86c</span></div>
        <div title="Code: 0xe86d" class="the-icons span3"><i class="demo-icon icn-finance">&#xe86d;</i> <span class="i-name">icn-finance</span><span class="i-code">0xe86d</span></div>
        <div title="Code: 0xe86e" class="the-icons span3"><i class="demo-icon icn-proyect">&#xe86e;</i> <span class="i-name">icn-proyect</span><span class="i-code">0xe86e</span></div>
      </div>
    </div>
    <div class="container footer">Generated by <a href="http://fontello.com">fontello.com</a></div>
  </body>
</html>

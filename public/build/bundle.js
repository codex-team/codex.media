var codex=function(e){function t(o){if(n[o])return n[o].exports;var a=n[o]={exports:{},id:o,loaded:!1};return e[o].call(a.exports,a,a.exports,t),a.loaded=!0,a.exports}var n={};return t.m=e,t.c=n,t.p="",t(0)}([function(e,t,n){n(1),codex=function(e){"use strict";return e.nodes={content:null},e.init=function(){e.content.customCheckboxes.init(),e.content.approvalButtons.init(),window.codexSpecial.init({blockId:"js-contrast-version-holder"}),e.core.log("Initialized","App init","info")},e}({}),codex.docReady=function(e){/in/.test(document.readyState)?window.setTimeout(codex.docReady,9,e):e()},codex.core=n(3),codex.ajax=n(4),codex.transport=n(5),codex.content=n(6),codex.appender=n(7),codex.parser=n(8),codex.comments=n(9),codex.alerts=n(10),e.exports=codex,codex.docReady(function(){codex.init()})},function(e,t){},,function(e,t){e.exports={log:function(e,t,n,o){var a=32;if(t){for(t=t.length<a?t:t.substr(0,a-2);t.length<a-1;)t+=" ";t+=":",e=t+e}n=n||"log";try{"console"in window&&window.console[n]&&(o?console[n](e,o):console[n](e))}catch(e){}},getOffset:function(e){var t,n,o,a;if(e)return e.getClientRects().length?(o=e.getBoundingClientRect(),o.width||o.height?(a=e.ownerDocument,n=window,t=a.documentElement,{top:o.top+n.pageYOffset-t.clientTop,left:o.left+n.pageXOffset-t.clientLeft}):o):{top:0,left:0}},isElementOnScreen:function(e){var t=codex.core.getOffset(e).top,n=window.scrollY+window.innerHeight;return n>t},css:function(e){return window.getComputedStyle(e)},insertAfter:function(e,t){e.parentNode.insertBefore(t,e.nextSibling)},replace:function(e,t){return e.parentNode.replaceChild(t,e)},insertBefore:function(e,t){e.parentNode.insertBefore(t,e)},random:function(e,t){return Math.floor(Math.random()*(t-e+1))+e},delegateEvent:function(e,t,n,o){e.addEventListener(n,function(e){for(var n,a=e.target;a&&!n;)n=a.matches(t),n||(a=a.parentElement);n&&o.call(e.target,e,a)},!0)},nodeTypes:{TAG:1,TEXT:3,COMMENT:8,DOCUMENT_FRAGMENT:11},keys:{BACKSPACE:8,TAB:9,ENTER:13,SHIFT:16,CTRL:17,ALT:18,ESC:27,SPACE:32,LEFT:37,UP:38,DOWN:40,RIGHT:39,DELETE:46,META:91},isDomNode:function(e){return e&&"object"==typeof e&&e.nodeType&&e.nodeType==this.nodeTypes.TAG},parseHTML:function(e){var t,n,o=[];t=document.createElement("div"),t.innerHTML=e.trim(),n=t.childNodes;for(var a,i=0;a=n[i];i++)(a.nodeType!=codex.core.nodeTypes.TEXT||a.textContent.trim())&&o.push(a);return o},isEmpty:function(e){return 0===Object.keys(e).length},isVisible:function(e){return null!==e.offsetParent},setCookie:function(e,t,n,o,a){var i=e+"="+t;n&&(i+="; expires="+n.toGMTString()),o&&(i+="; path="+o),a&&(i+="; domain="+a),document.cookie=i},getCookie:function(e){var t=document.cookie,n=e+"=",o=t.indexOf("; "+n);if(o==-1){if(o=t.indexOf(n),0!==o)return null}else o+=2;var a=document.cookie.indexOf(";",o);return a==-1&&(a=t.length),unescape(t.substring(o+n.length,a))}}},function(e,t){var n=function(){function e(e){return"function"==typeof e.append}var t=function(t){if(t&&t.url){var n=window.XMLHttpRequest?new window.XMLHttpRequest:new window.ActiveXObject("Microsoft.XMLHTTP"),o=function(){};t.async=!0,t.type=t.type||"GET",t.data=t.data||"",t["content-type"]=t["content-type"]||"application/json; charset=utf-8",o=t.success||o,"GET"==t.type&&t.data&&(t.url=/\?/.test(t.url)?t.url+"&"+t.data:t.url+"?"+t.data),t.withCredentials&&(n.withCredentials=!0),t.beforeSend&&"function"==typeof t.beforeSend&&t.beforeSend.call(),n.open(t.type,t.url,t.async),e(t.data)||n.setRequestHeader("Content-type",t["content-type"]),n.setRequestHeader("X-Requested-With","XMLHttpRequest"),n.onreadystatechange=function(){4==n.readyState&&200==n.status&&o(n.responseText)},n.send(t.data)}};return{call:t}}();e.exports=n},function(e,t){var n={transportURL:"/file/transport",input:null,type:null,keydownFinishedTimeout:null,files:{},prepare:function(){var e=document.createElement("INPUT");e.type="file",e.addEventListener("change",this.fileSelected),this.input=e},clearInput:function(){this.input=null,this.type=null},selectFile:function(e,t){this.prepare(),this.type=t,this.input.click()},fileSelected:function(){var e=n.type,t=this,o=t.files,a=new FormData;a.append("type",e),a.append("files",o[0],o[0].name),codex.ajax.call({type:"POST",url:n.transportURL,data:a,success:n.responseForPageForm,beforeSend:n.beforeSendPageForm}),n.clearInput()},beforeSendPageForm:function(){},responseForPageForm:function(e){e=JSON.parse(e),e.success&&e.title?n.storeFile(e):codex.alerts.show(e.message)},storeFile:function(e){e&&e.id&&(this.files[e.id]={title:e.title,id:e.id},this.appendFileRow(e))},appendFileRow:function(e){var t=document.getElementById("formAttaches"),n=document.createElement("div"),o=document.createElement("span"),a=document.createElement("span");switch(n.classList.add("item"),e.type){case"1":o.classList.add("item_file");break;case"2":o.classList.add("item_image")}o.textContent=e.title,o.setAttribute("contentEditable",!0),a.classList.add("fl_r","button-delete","icon-trash"),a.addEventListener("click",function(){this.parentNode.dataset.readyForDelete&&(delete codex.transport.files[e.id],this.parentNode.remove()),this.parentNode.dataset.readyForDelete=!0,this.classList.add("button-delete__ready-to-delete"),this.innerHTML="Удалить документ"},!1),n.appendChild(o),n.appendChild(a),t.appendChild(n),n.dataset.id=e.id,n.addEventListener("input",this.storeFileName,!1)},storeFileName:function(){codex.transport.keydownFinishedTimeout&&window.clearTimeout(codex.transport.keydownFinishedTimeout);var e=this;codex.transport.keydownFinishedTimeout=window.setTimeout(function(){var t=e.dataset.id,n=e.textContent.trim();n&&(codex.transport.files[t].title=n)},200)},submitAtlasForm:function(){var e=document.forms.atlas;if(e){var t=document.createElement("input");t.type="hidden",t.name="attaches",t.value=JSON.stringify(this.files),e.appendChild(t);var n=document.getElementById("json_result");codex.editor.saver.saveBlocks(),window.setTimeout(function(){n.innerHTML=JSON.stringify(codex.editor.state.jsonOutput),e.submit()},100)}}};e.exports=n},function(e,t){e.exports=function(){var e=function(e){var t=document.getElementById("js-mobile-menu-holder"),n="mobile-menu-holder--opened";t.classList.toggle(n),e.stopPropagation(),e.stopImmediatePropagation(),e.preventDefault()},t={CHECKED_CLASS:"checked",init:function(){var e=document.getElementsByClassName("js-custom-checkbox");if(e.length)for(var t=e.length-1;t>=0;t--)e[t].addEventListener("click",codex.content.customCheckboxes.clicked,!1)},clicked:function(){var e=this,t=this.querySelector("input"),n=this.classList.contains(codex.content.customCheckboxes.CHECKED_CLASS);e.classList.toggle(codex.content.customCheckboxes.CHECKED_CLASS),n?t.removeAttribute("checked"):t.setAttribute("checked","checked")}},n={CLICKED_CLASS:"click-again-to-approve",init:function(){var e=document.getElementsByClassName("js-approval-button");if(e.length)for(var t=e.length-1;t>=0;t--)e[t].addEventListener("click",codex.content.approvalButtons.clicked,!1)},clicked:function(e){var t=this,n=this.classList.contains(codex.content.approvalButtons.CLICKED_CLASS);n||(t.classList.add(codex.content.approvalButtons.CLICKED_CLASS),e.preventDefault())}};return{toggleMobileMenu:e,customCheckboxes:t,approvalButtons:n}}()},function(e,t){var n={page:1,settings:null,blockForItems:null,loadMoreButton:null,buttonText:null,init:function(e){return this.settings=e,this.loadMoreButton=document.getElementById(this.settings.button_id),!!this.loadMoreButton&&(this.blockForItems=document.getElementById(this.settings.target_block_id),!!this.blockForItems&&(this.page=e.current_page,this.buttonText=this.loadMoreButton.innerHTML,this.settings.autoLoading&&(this.autoLoading.isAllowed=!0),void this.loadMoreButton.addEventListener("click",function(e){codex.appender.load(),e.preventDefault(),codex.appender.autoLoading.init()},!1)))},load:function(){var e=this.settings.url+(parseInt(this.page)+1);codex.core.ajax({type:"post",url:e,data:{},beforeSend:function(){codex.appender.loadMoreButton.innerHTML=" ",codex.appender.loadMoreButton.classList.add("loading")},success:function(e){if(e=JSON.parse(e),e.success){if(!e.pages)return;codex.appender.blockForItems.innerHTML+=e.pages,codex.appender.page++,codex.appender.settings.autoLoading&&(codex.appender.autoLoading.canLoad=!0),e.next_page||codex.appender.disable()}else codex.core.showException("Не удалось подгрузить новости");codex.appender.loadMoreButton.classList.remove("loading"),codex.appender.loadMoreButton.innerHTML=codex.appender.buttonText}})},disable:function(){codex.appender.loadMoreButton.style.display="none",codex.appender.autoLoading.isLaunched&&codex.appender.autoLoading.disable()},autoLoading:{isAllowed:!1,isLaunched:!1,canLoad:!0,init:function(){this.isAllowed&&(window.addEventListener("scroll",codex.appender.autoLoading.scrollEvent),codex.appender.autoLoading.isLaunched=!0)},disable:function(){window.removeEventListener("scroll",codex.appender.autoLoading.scrollEvent),codex.appender.autoLoading.isLaunched=!1},scrollEvent:function(){var e=window.pageYOffset+window.innerHeight>=document.body.clientHeight;e&&codex.appender.autoLoading.canLoad&&(codex.appender.autoLoading.canLoad=!1,codex.appender.load())}}};e.exports=n},function(e,t){var n={input:null,init:function(){var e=this;this.input.addEventListener("paste",function(){e.inputPasteCallback()},!1)},inputPasteCallback:function(){var e=this.input,t=this;window.setTimeout(function(){t.sendRequest(e.value)},100)},sendRequest:function(e){codex.core.ajax({type:"get",url:"/ajax/get_page",data:{url:e},success:function(t){var n,o,a;1==t.success?(n=document.getElementById("page_form_title"),o=document.getElementById("page_form_content"),a=document.getElementById("source_link"),n.value=t.title,o.value=t.article,a.value=e,document.getElementsByClassName("redactor_redactor")[0].innerHTML=t.article):codex.core.showException("Не удалось импортировать страницу")}})}};e.exports=n},function(e,t){var n=function(){function e(){l.form=document.getElementById("comment_form"),l.parentId=l.form.parentId,l.rootId=l.form.rootId,l.addCommentButton=l.form.addCommentButton,l.addAnswerTo=document.getElementById("addAnswerTo"),l.cancelAnswerButton=document.getElementById("cancel_answer"),l.textarea=l.form.add_comment_textarea,s=document.getElementById("page_comments"),c=document.getElementsByClassName("comment__actions--answer"),t(),l.textarea.addEventListener("input",i,!1);for(var e=0;e<c.length;e++)c[e].addEventListener("click",o,!1);l.cancelAnswerButton.addEventListener("click",n,!1),l.textarea.addEventListener("keydown",r,!1)}function t(){l.textarea.value=""}function n(){s.appendChild(l.form),a({parentId:0,rootId:0})}function o(e){var t,n;t=e.target.dataset.commentId?e.target:e.target.parentNode,n=document.getElementById("comment_"+t.dataset.commentId),n.appendChild(l.form),a({parentId:t.dataset.commentId,rootId:t.dataset.rootId})}function a(e){if(l.parentId.value=e.parentId,l.rootId.value=e.rootId,e.parentId){var t=document.querySelector("#comment_"+e.parentId+" .author_name").innerHTML;l.addAnswerTo.innerHTML='<i class="icon-right-dir"></i> '+t,l.addCommentButton.value="Ответить"}else l.addAnswerTo.innerHTML="",l.addCommentButton.value="Оставить комментарий"}function i(){d()}function d(){var e=l.textarea.value.trim();l.addCommentButton.disabled=!e}function r(e){var t=e.ctrlKey||e.metaKey,n=13==e.keyCode;t&&n&&l.form.submit()}var c=null,s=null,l={form:null,parentId:null,rootId:null,addCommentButton:null,addAnswerTo:null,cancelAnswerButton:null,textarea:""};return{init:e}}();e.exports=n},function(e,t,n){e.exports=function(){function e(){return!!a||(a=document.createElement("DIV"),a.classList.add(o.wrapper),void document.body.appendChild(a))}function t(t){e();var n=document.createElement("DIV");n.classList.add(o.exception),n.innerHTML=t,a.appendChild(n),n.classList.add("bounceIn"),window.setTimeout(function(){n.remove()},8e3)}n(11);var o={wrapper:"exceptionWrapper",exception:"clientException"},a=null;return{show:t}}({})},function(e,t){}]);
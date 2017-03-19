var codex=function(e){function t(o){if(n[o])return n[o].exports;var a=n[o]={exports:{},id:o,loaded:!1};return e[o].call(a.exports,a,a.exports,t),a.loaded=!0,a.exports}var n={};return t.m=e,t.c=n,t.p="",t(0)}([function(e,t,n){n(1),codex=function(e){"use strict";return e.nodes={content:null},e.init=function(){e.content.customCheckboxes.init(),e.content.approvalButtons.init(),e.autoresizeTextarea.init(),window.codexSpecial.init({blockId:"js-contrast-version-holder"}),e.core.log("Initialized","App init","info")},e}({}),codex.docReady=function(e){/in/.test(document.readyState)?window.setTimeout(codex.docReady,9,e):e()},codex.core=n(2),codex.ajax=n(3),codex.transport=n(4),codex.content=n(5),codex.appender=n(6),codex.parser=n(7),codex.comments=n(8),codex.alerts=n(9),codex.autoresizeTextarea=n(11),codex.profileSettings=n(12),codex.sharer=n(13),e.exports=codex,codex.docReady(function(){codex.init()})},function(e,t){},function(e,t){e.exports={log:function(e,t,n,o){var a=32;if(t){for(t=t.length<a?t:t.substr(0,a-2);t.length<a-1;)t+=" ";t+=":",e=t+e}n=n||"log";try{"console"in window&&window.console[n]&&(o?console[n](e,o):console[n](e))}catch(e){}},getOffset:function(e){var t,n,o,a;if(e)return e.getClientRects().length?(o=e.getBoundingClientRect(),o.width||o.height?(a=e.ownerDocument,n=window,t=a.documentElement,{top:o.top+n.pageYOffset-t.clientTop,left:o.left+n.pageXOffset-t.clientLeft}):o):{top:0,left:0}},isElementOnScreen:function(e){var t=codex.core.getOffset(e).top,n=window.scrollY+window.innerHeight;return n>t},css:function(e){return window.getComputedStyle(e)},insertAfter:function(e,t){e.parentNode.insertBefore(t,e.nextSibling)},replace:function(e,t){return e.parentNode.replaceChild(t,e)},insertBefore:function(e,t){e.parentNode.insertBefore(t,e)},random:function(e,t){return Math.floor(Math.random()*(t-e+1))+e},delegateEvent:function(e,t,n,o){e.addEventListener(n,function(e){for(var n,a=e.target;a&&!n;)n=a.matches(t),n||(a=a.parentElement);n&&o.call(e.target,e,a)},!0)},nodeTypes:{TAG:1,TEXT:3,COMMENT:8,DOCUMENT_FRAGMENT:11},keys:{BACKSPACE:8,TAB:9,ENTER:13,SHIFT:16,CTRL:17,ALT:18,ESC:27,SPACE:32,LEFT:37,UP:38,DOWN:40,RIGHT:39,DELETE:46,META:91},isDomNode:function(e){return e&&"object"==typeof e&&e.nodeType&&e.nodeType==this.nodeTypes.TAG},parseHTML:function(e){var t,n,o=[];t=document.createElement("div"),t.innerHTML=e.trim(),n=t.childNodes;for(var a,r=0;a=n[r];r++)(a.nodeType!=codex.core.nodeTypes.TEXT||a.textContent.trim())&&o.push(a);return o},isEmpty:function(e){return 0===Object.keys(e).length},isVisible:function(e){return null!==e.offsetParent},setCookie:function(e,t,n,o,a){var r=e+"="+t;n&&(r+="; expires="+n.toGMTString()),o&&(r+="; path="+o),a&&(r+="; domain="+a),document.cookie=r},getCookie:function(e){var t=document.cookie,n=e+"=",o=t.indexOf("; "+n);if(o==-1){if(o=t.indexOf(n),0!==o)return null}else o+=2;var a=document.cookie.indexOf(";",o);return a==-1&&(a=t.length),unescape(t.substring(o+n.length,a))}}},function(e,t){var n=function(){function e(e){return"function"==typeof e.append}var t=function(t){if(t&&t.url){var n=window.XMLHttpRequest?new window.XMLHttpRequest:new window.ActiveXObject("Microsoft.XMLHTTP"),o=function(){};t.async=!0,t.type=t.type||"GET",t.data=t.data||"",t["content-type"]=t["content-type"]||"application/json; charset=utf-8",o=t.success||o,"GET"==t.type&&t.data&&(t.url=/\?/.test(t.url)?t.url+"&"+t.data:t.url+"?"+t.data),t.withCredentials&&(n.withCredentials=!0),t.beforeSend&&"function"==typeof t.beforeSend&&t.beforeSend.call(),n.open(t.type,t.url,t.async),e(t.data)||n.setRequestHeader("Content-type",t["content-type"]),n.setRequestHeader("X-Requested-With","XMLHttpRequest"),n.onreadystatechange=function(){4==n.readyState&&200==n.status&&o(n.responseText)},n.send(t.data)}};return{call:t}}();e.exports=n},function(e,t){e.exports=function(e){var t=null;e.input=null,e.init=function(a){if(!a.url)return void codex.core.log("can't send request because `url` is missed","Transport module","error");t=a;var r=document.createElement("INPUT");r.type="file",t&&t.multiple&&r.setAttribute("multiple","multiple"),t&&t.accept&&r.setAttribute("accept",t.accept),r.addEventListener("change",o,!1),e.input=r,n()};var n=function(){e.input.click()},o=function(){var n=t.url,o=t.beforeSend,a=t.success,r=t.error,i=new FormData,c=e.input.files;i.append("files",c[0],c[0].name),codex.ajax.call({type:"POST",data:i,url:n,beforeSend:o,success:a,error:r})};return e}({})},function(e,t){e.exports=function(){var e=function(e){var t=document.getElementById("js-mobile-menu-holder"),n="mobile-menu-holder--opened";t.classList.toggle(n),e.stopPropagation(),e.stopImmediatePropagation(),e.preventDefault()},t={CHECKED_CLASS:"checked",init:function(){var e=document.getElementsByClassName("js-custom-checkbox");if(e.length)for(var t=e.length-1;t>=0;t--)e[t].addEventListener("click",codex.content.customCheckboxes.clicked,!1)},clicked:function(){var e=this,t=this.querySelector("input"),n=this.classList.contains(codex.content.customCheckboxes.CHECKED_CLASS);e.classList.toggle(codex.content.customCheckboxes.CHECKED_CLASS),n?t.removeAttribute("checked"):t.setAttribute("checked","checked")}},n={CLICKED_CLASS:"click-again-to-approve",init:function(){var e=document.getElementsByClassName("js-approval-button");if(e.length)for(var t=e.length-1;t>=0;t--)e[t].addEventListener("click",codex.content.approvalButtons.clicked,!1)},clicked:function(e){var t=this,n=this.classList.contains(codex.content.approvalButtons.CLICKED_CLASS);n||(t.classList.add(codex.content.approvalButtons.CLICKED_CLASS),e.preventDefault())}};return{toggleMobileMenu:e,customCheckboxes:t,approvalButtons:n}}()},function(e,t){var n={page:1,settings:null,blockForItems:null,loadMoreButton:null,buttonText:null,init:function(e){return this.settings=e,this.loadMoreButton=document.getElementById(this.settings.buttonId),!!this.loadMoreButton&&(this.blockForItems=document.getElementById(this.settings.targetBlockId),!!this.blockForItems&&(this.page=e.currentPage,this.buttonText=this.loadMoreButton.innerHTML,this.settings.autoLoading&&(this.autoLoading.isAllowed=!0),void this.loadMoreButton.addEventListener("click",function(e){codex.appender.load(),e.preventDefault(),codex.appender.autoLoading.init()},!1)))},load:function(){var e=this.settings.url+(parseInt(this.page)+1);codex.ajax.call({type:"post",url:e,data:{},beforeSend:function(){codex.appender.loadMoreButton.classList.add("loading")},success:function(e){if(e=JSON.parse(e),e.success){if(!e.pages)return;codex.appender.blockForItems.innerHTML+=e.pages,codex.appender.page++,codex.appender.settings.autoLoading&&(codex.appender.autoLoading.canLoad=!0),e.next_page||codex.appender.disable()}else codex.core.showException("Не удалось подгрузить новости");codex.appender.loadMoreButton.classList.remove("loading")}})},disable:function(){codex.appender.loadMoreButton.style.display="none",codex.appender.autoLoading.isLaunched&&codex.appender.autoLoading.disable()},autoLoading:{isAllowed:!1,isLaunched:!1,canLoad:!0,init:function(){this.isAllowed&&(window.addEventListener("scroll",codex.appender.autoLoading.scrollEvent),codex.appender.autoLoading.isLaunched=!0)},disable:function(){window.removeEventListener("scroll",codex.appender.autoLoading.scrollEvent),codex.appender.autoLoading.isLaunched=!1},scrollEvent:function(){var e=window.pageYOffset+window.innerHeight>=document.body.clientHeight;e&&codex.appender.autoLoading.canLoad&&(codex.appender.autoLoading.canLoad=!1,codex.appender.load())}}};e.exports=n},function(e,t){var n={input:null,init:function(){var e=this;this.input.addEventListener("paste",function(){e.inputPasteCallback()},!1)},inputPasteCallback:function(){var e=this.input,t=this;window.setTimeout(function(){t.sendRequest(e.value)},100)},sendRequest:function(e){codex.core.ajax({type:"get",url:"/ajax/get_page",data:{url:e},success:function(t){var n,o,a;1==t.success?(n=document.getElementById("page_form_title"),o=document.getElementById("page_form_content"),a=document.getElementById("source_link"),n.value=t.title,o.value=t.article,a.value=e,document.getElementsByClassName("redactor_redactor")[0].innerHTML=t.article):codex.core.showException("Не удалось импортировать страницу")}})}};e.exports=n},function(e,t){e.exports=function(){function e(e){f=document.getElementById(e.listId),m&&p()}function t(e){if(!e.classList.contains(h.replyOpened)){var t={parentId:e.dataset.parentId,rootId:e.dataset.rootId,action:e.dataset.action},o=n(t);codex.core.insertAfter(e,o),e.classList.add(h.replyOpened),i(o).focus()}}function n(e){var t=o(),n=a(),r=document.createElement("DIV");return r.classList.add(h.replyForm),t.dataset.parentId=e.parentId,t.dataset.rootId=e.rootId,t.dataset.action=e.action,r.appendChild(t),r.appendChild(n),r}function o(){var e=document.createElement("TEXTAREA");return e.classList.add(h.replyTextarea),e.placeholder="Ваш комментарий",e.addEventListener("keydown",s,!1),e.addEventListener("blur",c,!1),codex.autoresizeTextarea.addListener(e),e}function a(){var e=document.createElement("DIV");return e.classList.add(h.replySubmitButton,"button","master"),e.textContent="Отправить",e.addEventListener("click",r,!1),e}function r(){var e=this,t=e.parentNode,n=i(t);u(n)}function i(e){return e.getElementsByTagName("TEXTAREA")[0]}function c(e){var t=e.target,n=t.parentNode,o=t.dataset.parentId;t.value.trim()||d(n,o)}function d(e,t){var n=document.getElementById("reply"+t);e.remove(),n.classList.remove(h.replyOpened)}function s(e){var t=e.ctrlKey||e.metaKey,n=13==e.keyCode,o=e.target;t&&n&&(u(o),e.preventDefault())}function u(e){var t=new FormData,n=e.parentNode,o=n.querySelector("."+h.replySubmitButton),a=e.dataset.rootId,r=e.dataset.parentId,i=e.dataset.action;t.append("root_id",a),t.append("parent_id",r),t.append("comment_text",e.value),t.append("csrf",window.csrf),codex.ajax.call({type:"POST",url:i,data:t,beforeSend:function(){o.classList.add("loading")},success:function(e){var t,a;o.classList.remove("loading"),e=JSON.parse(e),e.success?(d(n,r),a=document.querySelector(".js-empty-comments"),a&&a.remove(),t=codex.core.parseHTML(e.comment)[0],f.appendChild(t),window.scrollTo(0,document.body.scrollHeight),l(e.commentId)):codex.alerts.show(e.error)}})}function l(e){var t=document.getElementById("comment"+e);t.classList.add(h.highlighted),window.setTimeout(function(){t.classList.remove(h.highlighted)},500)}function p(){var e,t=m.match(/\d+/);t&&(e=t[0],l(e))}var f=null,m=document.location.hash,h={replyForm:"comments-form",replyTextarea:"comment-form__text",replyOpened:"comment-form__placeholder--opened",replySubmitButton:"comment-form__button",highlighted:"comment--highligthed"};return{init:e,reply:t}}()},function(e,t,n){e.exports=function(){function e(){return!!a||(a=document.createElement("DIV"),a.classList.add(o.wrapper),void document.body.appendChild(a))}function t(t){e();var n=document.createElement("DIV");n.classList.add(o.exception),n.innerHTML=t,a.appendChild(n),n.classList.add("bounceIn"),window.setTimeout(function(){n.remove()},8e3)}n(10);var o={wrapper:"exceptionWrapper",exception:"clientException"},a=null;return{show:t}}({})},function(e,t){},function(e,t){e.exports=function(){var e=function(){var e=document.getElementsByClassName("js-autoresizable");if(e.length)for(var n=0;n<e.length;n++)t(e[n])},t=function(e){e.addEventListener("input",n,!1)},n=function(e){var t=e.target;o(t)},o=function(e){e.scrollHeight>e.clientHeight&&(e.style.height=e.scrollHeight+"px")};return{init:e,addListener:t}}()},function(e,t){e.exports=function(){var e=function(){t()},t=function(){var e=document.getElementById("repeat-email-confirmation");e.addEventListener("click",n)},n=function(e){var t=function(t){t=JSON.parse(t),codex.alerts.show(t.message),e.target.classList.remove("loading")};e.target.classList.add("loading"),codex.ajax.call({url:"/ajax/confirmation-email",success:t})};return{init:e}}()},function(e,t){var n={init:function(){for(var e=document.querySelectorAll(".js-share"),t=e.length-1;t>=0;t--)e[t].addEventListener("click",n.click,!0)},shareVk:function(e){var t="https://vk.com/share.php?";t+="url="+e.url,t+="&title="+e.title,t+="&description="+e.desc,t+="&image="+e.img,t+="&noparse=true",this.popup(t,"vkontakte")},shareFacebook:function(e){var t=0x62eef6f1917ee,n="https://www.facebook.com/dialog/share?display=popup";n+="&app_id="+t,n+="&href="+e.url,n+="&redirect_uri="+document.location.href,this.popup(n,"facebook")},shareTwitter:function(e){var t="https://twitter.com/share?";t+="text="+e.title,t+="&url="+e.url,t+="&counturl="+e.url,this.popup(t,"twitter")},shareTelegram:function(e){var t="https://telegram.me/share/url";t+="?text="+e.title,t+="&url="+e.url,this.popup(t,"telegram")},popup:function(e,t){window.open(e,"","toolbar=0,status=0,width=626,height=436"),window.yaCounter32652805&&window.yaCounter32652805.reachGoal("article-share",function(){},this,{type:t,url:e})},click:function(e){var t=e.target,o=t.dataset.shareType||t.parentNode.dataset.shareType;if(n[o]){var a={url:t.dataset.url||t.parentNode.dataset.url,title:t.dataset.title||t.parentNode.dataset.title,desc:t.dataset.desc||t.parentNode.dataset.desc,img:t.dataset.img||t.parentNode.dataset.title};n[o](a)}}};e.exports=n}]);
//# sourceMappingURL=bundle.js.map
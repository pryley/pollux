pollux.dependency={},pollux.editors={},pollux.featured={},pollux.metabox={},pollux.tabs={},pollux.classListAction=function(e){return e?"add":"remove"},pollux.dependency.ajax=function(e,t,l){var n=pollux.dependency.getAjaxOptions(t);wp.ajax.send({error:n.error,success:n.success,data:{_ajax_nonce:wp.updates.ajaxNonce,action:e,plugin:n.plugin,type:l}})},pollux.dependency.getAjaxOptions=function(e){return{error:pollux.dependency.onError.bind(e),plugin:e.getAttribute("data-plugin"),slug:e.getAttribute("data-slug"),success:pollux.dependency.onSuccess.bind(e)}},pollux.dependency.init=function(){pollux.dependency.buttons=document.querySelectorAll(".pollux-notice a.button"),[].forEach.call(pollux.dependency.buttons,function(e){e.addEventListener("click",pollux.dependency.onClick)})},pollux.dependency.install=function(e,t){return pollux.dependency.updateButtonText(e,"pluginInstallingLabel"),e.classList.add("updating-message"),wp.updates.ajax("install-plugin",t)},pollux.dependency.onClick=function(e){var t=this.href.match(/action=([^&]+)/);null!==t&&(t=t[1].split("-")[0],pollux.dependency[t]&&(this.blur(),e.preventDefault(),this.classList.contains("updating-message")||pollux.dependency[t](this,pollux.dependency.getAjaxOptions(this))))},pollux.dependency.onError=function(){window.location=this.href},pollux.dependency.onSuccess=function(e){var t=this,l=e.install?"install":"update";if(!e.activate_url)return pollux.dependency.ajax("pollux/dependency/activate_url",t,l);pollux.dependency.setUpdatedMessage(t,l),e.activate_url&&setTimeout(function(){pollux.dependency.setActivateButton(t,e)},1e3)},pollux.dependency.setActivateButton=function(e,t){e.classList.remove("updated-message"),e.classList.remove("button-disabled"),e.classList.add("button-primary"),e.href=t.activate_url,pollux.dependency.updateButtonText(e,"activatePluginLabel")},pollux.dependency.setUpdatedMessage=function(e,t){e.classList.remove("updating-message"),e.classList.add("updated-message"),e.classList.add("button-disabled"),pollux.dependency.updateButtonText(e,"install"===t?"pluginInstalledLabel":"updatedLabel")},pollux.dependency.updateButtonText=function(e,t){if(wp.updates.l10n[t]){var l=wp.updates.l10n[t].replace("%s",e.getAttribute("data-name"));e.innerHTML!==l&&(e.innerHTML=l)}},pollux.dependency.upgrade=function(e,t){return pollux.dependency.updateButtonText(e,"updatingLabel"),e.classList.add("updating-message"),wp.updates.ajax("update-plugin",t)},pollux.editors.disable=function(e){pollux.editors.all[e].setOption("theme","disabled"),pollux.editors.all[e].setOption("readOnly","nocursor")},pollux.editors.enable=function(e){pollux.editors.all[e].setOption("theme","pollux"),pollux.editors.all[e].setOption("readOnly",!1)},pollux.editors.init=function(){pollux.editors.all=[],[].forEach.call(document.querySelectorAll(".pollux-code"),function(e,t){pollux.editors.all[t]=CodeMirror.fromTextArea(e,{gutters:["CodeMirror-lint-markers"],highlightSelectionMatches:{wordsOnly:!0},lineNumbers:!0,lint:!0,mode:"text/yaml",showInvisibles:!0,showTrailingSpace:!0,styleActiveLine:!0,tabSize:2,theme:"pollux",viewportMargin:1/0}),pollux.editors.all[t].setOption("extraKeys",{Tab:function(e){var t=Array(e.getOption("indentUnit")+1).join(" ");e.replaceSelection(t)}}),pollux.editors.all[t].display.wrapper.setAttribute("data-disabled",e.getAttribute("data-disabled")),e.readOnly&&pollux.editors.disable(t)})},pollux.featured.init=function(){jQuery("#postimagediv").on("click","#pollux-set-featured",function(e){e.preventDefault(),wp.media.view.settings.post.featuredImageId=Math.round(jQuery("#featured").val()),pollux.featured.frame=wp.media.featuredImage.frame,pollux.featured.frame().open()}).on("click","#pollux-remove-featured",function(e){e.preventDefault(),pollux.featured.set(-1)})},pollux.featured.select=function(){if(wp.media.view.settings.post.featuredImageId){var e=this.get("selection").single();pollux.featured.set(e?e.id:-1)}},pollux.featured.set=function(e){wp.media.view.settings.post.featuredImageId=Math.round(e),wp.media.post("pollux/archives/featured/html",{_wpnonce:document.querySelector("#_wpnonce").value,post_type:document.querySelector("#archive-type").value,thumbnail_id:e}).done(function(e){document.querySelector("#postimagediv > .inside").innerHTML=e})},pollux.metabox.hasValue=function(e){return"checkbox"===e.type?!0===e.checked:""!==e.value},pollux.metabox.init=function(){var e=document.querySelectorAll(".rwmb-input [data-depends]");[].forEach.call(e,function(e){var t=pollux.metabox.setVisibility(e),l=-1!==["checkbox","radio","select-one","select-multiple"].indexOf(t.type)?"change":"keyup";t.addEventListener(l,function(){pollux.metabox.setVisibility(e)})})},pollux.metabox.setVisibility=function(e){var t=document.getElementById(e.getAttribute("data-depends")),l=pollux.classListAction(!pollux.metabox.hasValue(t));return e.closest(".rwmb-field").classList[l]("hidden"),t},pollux.tabs.init=function(){pollux.tabs.active=document.querySelector("#pollux-active-tab"),pollux.tabs.referrer=document.querySelector('input[name="_wp_http_referer"]'),pollux.tabs.tabs=document.querySelectorAll(".pollux-tabs a"),pollux.tabs.views=document.querySelectorAll(".pollux-config .form-table"),[].forEach.call(pollux.tabs.tabs,function(e,t){(location.hash?e.getAttribute("href").slice(1)===location.hash.slice(2):0===t)&&pollux.tabs.setTab(e),e.addEventListener("click",pollux.tabs.onClick),e.addEventListener("touchend",pollux.tabs.onClick)})},pollux.tabs.onClick=function(e){e.preventDefault(),this.blur(),pollux.tabs.setTab(this),location.hash="!"+this.getAttribute("href").slice(1)},pollux.tabs.setReferrer=function(e){var t=pollux.tabs.referrer.value.split("#")[0]+"#!"+pollux.tabs.views[e].id;pollux.tabs.referrer.value=t},pollux.tabs.setTab=function(e){[].forEach.call(pollux.tabs.tabs,function(t,l){var n=pollux.classListAction(t===e);"add"===n&&(pollux.tabs.active.value=pollux.tabs.views[l].id,pollux.tabs.setReferrer(l),pollux.tabs.setView(l)),t.classList[n]("nav-tab-active")})},pollux.tabs.setView=function(e){[].forEach.call(pollux.tabs.views,function(t,l){var n=pollux.classListAction(l!==e);t.classList[n]("ui-tabs-hide")})},jQuery(function(){for(var e in pollux)pollux.hasOwnProperty(e)&&pollux[e].init&&pollux[e].init()});
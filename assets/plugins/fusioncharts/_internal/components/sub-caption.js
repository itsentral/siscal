import{parseUnsafeString,pluck,pluckNumber,NORMAL,BOLD,setLineHeight,visibleStr,hiddenStr}from'../lib/lib';import{convertColor}from'../lib/lib-graphics';import Caption from'./caption';import{addDep}from'../dependency-manager';import subcaptionAnimation from'../animation-rules/subcaption-animation';let mathMax=Math.max,PXSTRING='px',POSITION_CENTER='center',POSITION_TOP='top',POSITION_BOTTOM='bottom',POSITION_MIDDLE='middle';addDep({name:'subcaptionAnimation',type:'animationRule',extension:subcaptionAnimation});class SubCaption extends Caption{getType(){return'caption'}getName(){return'subCaption'}configure(){let a=this,b=a.getFromEnv('chart'),c=b.getFromEnv('chart-attrib'),d=a.config||{},e=b.getFromEnv('style'),f=b.getChildren('caption')[0],g=e.outCanfontFamily,h=e.outCancolor,i=e.fontSize,j=['top','center'];switch(d.text=parseUnsafeString(c.subcaption),d.align||(d.align=''),d.align=pluck(c.captionposition,c.captionalignment,POSITION_CENTER),f.config.align&&(j=f.config.align.split('-'),j[0]&&(j[0]=j[0].toLowerCase()),j[1]&&(j[1]=j[1].toLowerCase()),2>j.length&&(j[1]=j[0])),j[0]){case POSITION_TOP:d.isOnTop=1;break;case POSITION_BOTTOM:d.isOnTop=0;break;default:d.isOnTop=pluckNumber(c.captionontop,1);}d.alignWithCanvas=pluckNumber(b.aligncaptionwithcanvas,c.aligncaptionwithcanvas,1),d.horizontalPadding=pluckNumber(c.captionhorizontalpadding,f.config.alignWithCanvas?0:15),d.style={fontFamily:pluck(c.subcaptionfont,c.captionfont,g),color:convertColor(pluck(c.subcaptionfontcolor,c.captionfontcolor,h).replace(/^#? ([a-f0-9]+)/ig,'#$1')),fontSize:pluckNumber(c.subcaptionfontsize,pluckNumber(mathMax(pluckNumber(c.captionfontsize)-3,-1),i)+pluckNumber(b.subTitleFontSizeExtender,1))+PXSTRING,fontWeight:0===pluckNumber(c.subcaptionfontbold,b.subTitleFontWeight,c.captionfontbold)?NORMAL:BOLD},setLineHeight(d.style)}draw(){let a,b,c=this,d=c.getFromEnv('chart'),e=d.getFromEnv('animationManager'),f=d.getChildren('caption')[0],g=d.config,h=g.textDirection,i=d.getChildContainer().captionGroup,j=c.getGraphicalElement('subCaptionElement'),k=c.getFromEnv('toolTipController'),l=c.config,m=l.style,n=l.text,o=f.config.align;n?(a={text:l.text,fill:m.color,x:l.x,y:l.y,"text-anchor":o||POSITION_MIDDLE,"vertical-align":POSITION_TOP,direction:h,visibility:f.config.drawCaption?visibleStr:hiddenStr},j=c.addGraphicalElement('subCaptionElement',e.setAnimation({el:j||'text',attr:a,container:i,state:b,component:c,hookFn:function(){this.css(m)},label:'text'})),j.css(m),g.showtooltip?k.enableToolTip(j,l.originalText):k.disableToolTip(j)):j&&c.removeGraphicalElement(j),g.savedSubCaption=j}setDimention(a){this.config.x=a.x,this.config.y=a.y}}export default SubCaption;
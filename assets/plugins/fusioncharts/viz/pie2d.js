import{default as Pie2DDataset}from'../_internal/datasets/pie2d';import CommonAPI from'./commonchartapi';import{pluck,pluckNumber,componentFactory,POSITION_BOTTOM,POSITION_RIGHT,ZEROSTRING,ONESTRING}from'../_internal/lib/lib';import{convertColor}from'../_internal/lib/lib-graphics';import Caption from'../_internal/components/caption';import SubCaption from'../_internal/components/sub-caption';import Background from'../_internal/components/background';import datasetFactory from'../_internal/factories/pie-dataset';import legendItemFactory from'../_internal/factories/legend';import{_manageLegendSpace}from'../_internal/common-chart-api/legend-spacemanager';import{priorityList}from'../_internal/schedular';let math=Math,mathMin=math.min,mathMax=math.max,mathAbs=math.abs,mathPI=math.PI,mathRound=math.round,deg2rad=mathPI/180,rad2deg=180/mathPI,count=0,performSlicing=(a,b,c)=>{let d,e,f,g;return a?(d=a.components&&a.components.data||[],b=a.config.reversePlotOrder?d.length-b-1:b,g=d[b],g&&(e=g.config,f=(a=>!!c!==a.config.sliced||'undefined'==typeof c)(g)?a.plotGraphicClick.call(g.graphics.element):e.sliced),f):f};class Pie2D extends CommonAPI{static getName(){return'Pie2D'}constructor(){super(),this.defaultSeriesType='pie',this.defaultPlotShadow=1,this.reverseLegend=1,this.defaultPaletteOptions=void 0,this.sliceOnLegendClick=!0,this.dontShowLegendByDefault=!0,this.defaultZeroPlaneHighlighted=!1,this.hasCanvas=!0,this.eiMethods={isPlotItemSliced:function(a){var b,c,d=this.apiInstance,e=d&&d.getDatasets();return e&&(e=e[0])&&(b=e.components.data)&&b[a]&&(c=b[a].config)&&c.sliced},addData:function(){var a=this.apiInstance,b=a&&a.getDatasets();return b&&(b=b[0])&&b.addData.apply(b,arguments)},removeData:function(){var a=this.apiInstance,b=a&&a.getDatasets();return b&&(b=b[0])&&b.removeData.apply(b,arguments)},updateData:function(){var a=this.apiInstance,b=a&&a.getDatasets();return b&&(b=b[0])&&b.updateData.apply(b,arguments)},slicePlotItem:function(a,b,c){var d=this,e=d.apiInstance;return c?void e.addJob(`eiMethods-slice-plot${count++}`,function(){let d=performSlicing(e.getDatasets()[0],a,b);return'function'==typeof c&&c(d)},priorityList.postRender):performSlicing(e.getDatasets()[0],a,b)},startingAngle:function(a,b,c){var d,e=this.apiInstance;return c?void e.addJob(`eiMethods-start-angle${count++}`,function(){d=e._startingAngle(a,b),'function'==typeof c&&c(d)},priorityList.postRender):e._startingAngle(a,b)}},this.registerFactory('dataset',datasetFactory,['vCanvas','legend']),this.registerFactory('legend',legendItemFactory)}__setDefaultConfig(){super.__setDefaultConfig();let a=this.config;a.alignCaptionWithCanvas=0,a.formatnumberscale=1,a.isSingleSeries=!0,a.friendlyName='Pie Chart',a.defaultDatasetType='Pie2D',a.plotborderthickness=1,a.decimals=2,a.alphaanimation=0,a.singletonPlaceValue=!0,a.usedataplotcolorforlabels=0,a.enableslicing=ONESTRING,a.skipCanvasDrawing=!0}parseChartAttr(a){super.parseChartAttr(a);let b=this,c=b.getFromEnv('chart-attrib');b.config.showLegend=pluckNumber(c.showlegend,0)}configureAttributes(a){var b,c=this,d=c.config;c.parseChartAttr(a),c.createComponent(a),c.config.skipConfigureIteration.axis=!0,c.configureChildren(),c._createToolBox(),b=c.getFromEnv('toolTipController'),b.setStyle({bgColor:convertColor(d.tooltipbgcolor||'FFF',d.tooltipbgalpha||100),rawBgColor:(d.tooltipbgcolor||'FFF').replace(/^#?([a-f0-9]+)/ig,'#$1'),fontColor:(d.tooltipcolor||d.basefontcolor||'545454').replace(/^#?([a-f0-9]+)/ig,'#$1'),borderColor:convertColor(d.tooltipbordercolor||'666',d.tooltipborderalpha||100),rawBorderColor:(d.tooltipbordercolor||'666').replace(/^#?([a-f0-9]+)/ig,'#$1'),bgAlpha:pluckNumber(d.tooltipbgalpha,100),borderThickness:pluckNumber(d.tooltipborderthickness,1),showToolTipShadow:pluckNumber(d.showtooltipshadow||0),borderRadius:pluckNumber(d.tooltipborderradius,0),"font-size":d.basefontsize||10,"font-family":d.basefont||this.getFromEnv('style').inCanfontFamily,padding:pluckNumber(d.tooltippadding||3),borderAlpha:pluckNumber(d.tooltipborderalpha,100)})}_createLayers(){super._createLayers();let a=this.getFromEnv('animationManager');this.getChildContainer('legendGroup')||this.addChildContainer('legendGroup',a.setAnimation({el:'group',attr:{name:'legend'},component:this,container:this.getContainer('parentgroup'),label:'group'}))}createComponent(){let a,b=this;a=b.config.skipConfigureIteration={},b.createBaseComponent(),b.getFromEnv('animationManager').setAnimationState(b._firstConfigure?'initial':'update'),componentFactory(b,Caption,'caption'),a.caption=!0,componentFactory(b,SubCaption,'subCaption'),a.subCaption=!0,componentFactory(b,Background,'background'),a.background=!0,a.canvas=!0,b._createConfigurableComponents&&b._createConfigurableComponents(),b.config.realtimeEnabled&&b._realTimeConfigure&&b._realTimeConfigure()}_postSpaceManagement(){this.config.showLegend&&this.getChildren('legend')&&this.getChildren('legend')[0].postSpaceManager()}_checkInvalidSpecificData(){var a,b,c,d=this,e=d.getFromEnv('dataSource'),f=0,g=0,h=e.data;if(!h)return!0;for(b=h.length||0,a=0;a<b;a++)c=+h[a].value,f+=isNaN(c)||0!==c?0:1,g+=isNaN(c)?1:0;return!!(f+g>=b)}_spaceManager(){var a,b,c,d,e=this,f=e.config,g=e.getChildren('dataset')[0],h=g.components.data,i=g.config,j=e.getFromEnv('legend'),k=e.getFromEnv('color-manager'),l=e.getFromEnv('smartLabel'),m=[],n=i.dataLabelCounter,o=0,p=e.getFromEnv('dataSource').chart,q=pluckNumber(p.managelabeloverflow,0),r=pluckNumber(p.slicingdistance),s=i.preSliced||f.allPlotSliceEnabled!==ZEROSTRING||p.showlegend===ONESTRING&&p.interactivelegend!==ZEROSTRING?mathAbs(pluckNumber(r,20)):0,t=pluckNumber(p.pieradius,0),u=pluckNumber(p.enablesmartlabels,p.enablesmartlabel,1),v=u?pluckNumber(p.skipoverlaplabels,p.skipoverlaplabel,1):0,w=pluckNumber(p.issmartlineslanted,1),x=n?pluckNumber(p.labeldistance,p.nametbdistance,5):s,y=pluckNumber(p.smartlabelclearance,5),z=f.width,A=f.height,B=(e._manageActionBarSpace(.225*A)||{}).bottom,C=z-(f.marginRight+f.marginLeft),D=A-(f.marginTop+f.marginBottom)-(B?B+f.marginBottom:0),E=mathMin(D,C),F=pluck(p.smartlinecolor,k.getColor('plotFillColor')),G=pluckNumber(p.smartlinealpha,100),H=pluckNumber(p.smartlinethickness,.7),I=i.dataLabelOptions=g._parseDataLabelOptions(),J=I.style,K=n?pluckNumber(parseInt(J.lineHeight,10),12):0,L=0===t?.15*E:t,M=2*L,N={bottom:0,right:0},O=i.pieYScale,P=i.pieSliceDepth;if(I.connectorWidth=H,I.connectorPadding=pluckNumber(p.connectorpadding,5),I.connectorColor=convertColor(F,G),n&&(u&&(x=y),x+=s),d=M+2*(K+x),a=e._manageChartMenuBar(d<D?D-d:D/2),D-=(a.top||0)+(a.bottom||0),i.showLegend&&(e.config.hasLegend=!0,pluck(p.legendposition,POSITION_BOTTOM).toLowerCase()===POSITION_RIGHT?(N=j._manageLegendPosition(D/2),C-=N.right):(N=j._manageLegendPosition(D/2),D-=N.bottom)),e._allocateSpace(N),l.useEllipsesOnOverflow(f.useEllipsesWhenOverflow),1!==n)for(;n--;)l.setStyle(h[n].config.style||f.dataLabelStyle),m[n]=b=l.getOriSize(h[n].config.displayValue),o=mathMax(o,b.width);0===t?L=e._stubRadius(C,o,D,x,s,K,L):(i.slicingDistance=s,i.pieMinRadius=L,I.distance=x),c=D-2*(L*O+K),i.managedPieSliceDepth=P>c?P-c:i.pieSliceDepth,I.isSmartLineSlanted=w,I.enableSmartLabels=u,I.skipOverlapLabels=v,I.manageLabelOverflow=q}_stubRadius(a,b,c,d,e,f,g){var h,i=this,j=i.getChildren('dataset')[0],k=j.config,l=i.getFromEnv('dataSource').chart,m=pluckNumber(l.slicingdistance),n=k.dataLabelOptions||(k.dataLabelOptions=j._parseDataLabelOptions()),o=0;return o=mathMin(a/2-b-e,c/2-f)-d,o>=g?g=o:!m&&(h=g-o,e=d=mathMax(mathMin(d-h,e),10)),k.slicingDistance=e,k.pieMinRadius=g,n.distance=d,g}_startingAngle(a,b){var c,d=this,e=d.getChildren('dataset')[0],f=e.config,g=(c=f.startAngle)*-rad2deg+(0>-1*c?360:0);return isNaN(a)||f.singletonCase||f.isRotating||(a+=b?g:0,f.startAngle=-a*deg2rad,e._rotate(a),g=a),mathRound(100*((g%=360)+(0>g?360:0)))/100}_manageLegendSpace(){_manageLegendSpace.call(this)}getDSdef(){return Pie2DDataset}}export default Pie2D;
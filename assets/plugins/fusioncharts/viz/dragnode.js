import DragBase from'./dragbase';import DragNodeDataset from'../_internal/datasets/dragnode';import DragNodeConnector from'../_internal/datasets/connector';import DragNodeLabels from'../_internal/datasets/dragablelabels';import DragNodeGroup from'../_internal/datasets/groups/dragnode';import{getValidValue,pluck,pluckNumber,ZEROSTRING,extend2}from'../_internal/lib/lib';import{getDepsByType}from'../_internal/dependency-manager';import datasetFactory from'../_internal/factories/dragnode-dataset';import{submitData}from'../_internal/misc/editable-charts';import axisFactory from'../_internal/factories/xy-axis';import{_mouseEvtHandler}from'./basechart';let configurer;class DragNode extends DragBase{static getName(){return'DragNode'}constructor(){super();var a=this;a.fireGroupEvent=!0,a.usesXYinCategory=!0,a.dontShowLegendByDefault=!0,this.registerFactory('dataset',datasetFactory,['vCanvas']),this.registerFactory('axis',axisFactory,['canvas'])}getName(){return'DragNode'}_checkInvalidSpecificData(){let a=this.getFromEnv('dataSource'),b=a.dataset;if(!b)return!0}_mouseEvtHandler(a,b){_mouseEvtHandler(this,a,b)}parseChartAttr(a){var b,c=this,d=c.getFromEnv('dataSource'),e=d.chart,f=getDepsByType('transcoder');super.parseChartAttr.call(this,a),b=c.config,b.formAction=getValidValue(e.formaction),b.showLegend=pluckNumber(e.showlegend,0),e.submitdataasxml!==ZEROSTRING||e.formdataformat||(e.formdataformat=f.csv().format),b.formDataFormat=pluck(e.formdataformat,f.xml().format),b.formTarget=pluck(e.formtarget,'_self'),b.formMethod=pluck(e.formmethod,'POST'),b.submitFormAsAjax=pluckNumber(e.submitformusingajax,1),b.viewMode=pluckNumber(e.viewmode,0),b.drawTrendRegion=0}__setDefaultConfig(){super.__setDefaultConfig();var a=this.config;a.hasLegend=!0,a.friendlyName='Dragable Node Chart',a.defaultDatasetType='dragnode',a.limitUpdaterEnabled=!1,a.skipClipping=!0,a.numVDivLines=0,a.numDivLines=0,a.setadaptivexmin=1,a.showLimits=0,a.showdivlinevalues=0,a.showzeroplane=0,a.showyaxisvalues=0,a.enablemousetracking=!0,a.showzeroplaneontop=0}addConfigureOptions(){var a,b,c=this,d=c.config,e=d.restoreBtnTitle,f=d.submitBtnTitle,g=c.getFromEnv('chartMenuList'),h=c.config.viewMode,i=[{"Add Node":{handler:function(){let a=c.getChildren('canvas')[0].getChildren('vCanvas')[0].getChildren('datasetGroup_dragNode')[0];a.showNodeAddUI()},action:'click'}},{"Add Connector":{handler:function(){let a=c.getChildren('canvas')[0].getChildren('vCanvas')[0].getChildren('datasetGroup_dragNode')[0];a.showConnectorAddUI()},action:'click'}},{"Add Label":{handler:function(){let a=c.getChildren('canvas')[0].getChildren('vCanvas')[0].getChildren('datasetGroup_dragNode')[0];a.showLabelUpdateUI()},action:'click'}}];d.showRestoreBtn&&(a={},a[e]={handler:function(){let a=c.getChildren('canvas')[0].getChildren('vCanvas')[0].getChildren('datasetGroup_dragNode')[0];a.restoreData()},action:'click'},i.push(a)),d.showFormBtn&&(b={},b[f]={handler:function(){submitData.call(c)},action:'click'},i.push(b)),h||g.appendAsList(i)}getDSdef(a){return'connector'===a?DragNodeConnector:'dragnode'===a?DragNodeDataset:'dragableLabels'===a?DragNodeLabels:void 0}getDSGroupdef(){return DragNodeGroup}_setCategories(){var a,b,c=this,d=c.getFromEnv('dataSource'),e=c.getChildren('xAxis'),f=d.categories&&d.categories[0].category||[],g=f.length,h=[];for(a=0;a<g;a++)b=f[a]||{},void 0!==b.x&&h.push(b);e&&e[0].setTickValues(h)}getJSONData(){var a,b=this,c=b.getChildren('canvas')[0],d=c.getChildren('vCanvas')[0],e=d.getChildren('datasetGroup_dragNode')[0],f=b.getFromEnv('dataSource'),g={};return e&&(g=e.getJSONData()),a=extend2({},f),a.dataset=g.dataset,a.connectors=g.connectors,a.labels=g.labels,a}}configurer=DragNode.prototype.configure;export default DragNode;export{configurer};
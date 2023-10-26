import SSCartesian from'./sscartesian';import AreaDataset from'../_internal/datasets/area';import{HUNDREDSTRING,preDefStr}from'../_internal/lib/lib';let UNDEF,SEVENTYSTRING=preDefStr.SEVENTYSTRING;class Area2D extends SSCartesian{static getName(){return'Area2D'}constructor(){super(),this.defaultPlotShadow=0}getName(){return'Area2D'}__setDefaultConfig(){super.__setDefaultConfig();let a=this.config;a.friendlyName='Area Chart',a.singleseries=!0,a.defaultDatasetType='area',a.anchorborderthickness=1,a.anchorimageurl=UNDEF,a.anchorimagepadding=1,a.anchorsides=1,a.anchoralpha=UNDEF,a.anchorbgalpha=HUNDREDSTRING,a.anchorimagealpha=HUNDREDSTRING,a.anchorimagescale=100,a.anchorstartangle=90,a.anchorshadow=0,a.anchorbgcolor=UNDEF,a.anchorbordercolor=UNDEF,a.anchorradius=3,a.showvalues=1,a.plotfillalpha=SEVENTYSTRING,a.linedashlen=5,a.linedashgap=4,a.linedashed=UNDEF,a.linealpha=HUNDREDSTRING,a.linethickness=2,a.drawfullareaborder=1,a.connectnulldata=0,a.enablemousetracking=!0,a.defaultcrosslinethickness=1}getDSdef(){return AreaDataset}}export default Area2D;
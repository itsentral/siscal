import Pareto2D from'./pareto2d';import{preDefStr}from'../_internal/lib/lib';import{ParetoColumn3DDataset}from'../_internal/datasets/pareto3d';import{ParetoLineDataset}from'../_internal/datasets/paretoline';import canvas3dFactory from'../_internal/factories/canvas-3d-axis-ref-cartesian';class Pareto3D extends Pareto2D{static getName(){return'Pareto3D'}constructor(){super(),this.fireGroupEvent=!0,this.defaultPlotShadow=1,this.isPercentage=!0,this.registerFactory('canvas',canvas3dFactory)}getName(){return'Pareto3D'}__setDefaultConfig(){super.__setDefaultConfig();let a=this.config;a.is3D=!0,a.friendlyName='3D Pareto Chart',a.singleseries=!0,a.hasLegend=!1,a.defaultDatasetType='column3d',a.plotfillalpha=preDefStr.NINETYSTRING,a.use3dlineshift=1,a.enablemousetracking=!0,a.showzeroplaneontop=0}getDSdef(a){return'column'===a?ParetoColumn3DDataset:ParetoLineDataset}}export default Pareto3D;
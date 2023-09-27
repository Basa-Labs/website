"use strict";(self.webpackChunkburst_statistics=self.webpackChunkburst_statistics||[]).push([[910],{7333:(e,t,a)=>{a.d(t,{Z:()=>f});var s=a(9307),r=(a(9196),a(8197)),l=a(7429),n=a(1491),i=a(4046),c=a(9749),o=a(5736),u=a(5678),d=a(9546),m=a(8518);const f=e=>{let{filter:t,filterValue:a,label:f,children:b,startDate:g,endDate:_}=e;if(!t||!a)return(0,s.createElement)(s.Fragment,null,b);const p=(0,r.y)((e=>e.setFilters)),y=(0,r.y)((e=>e.setAnimate)),E=(0,l.q)((e=>e.goalFields)),v=(0,n.B)((e=>e.setMetrics)),D=(0,n.B)((e=>e.metrics)),h=(0,i.Y)((e=>e.setStartDate)),w=(0,i.Y)((e=>e.setEndDate)),N=(0,i.Y)((e=>e.setRange)),k=f?(0,o.__)("Click to filter by:","burst-statistics")+" "+f:(0,o.__)("Click to filter","burst-statistics");return(0,s.createElement)(c.Z,{title:k},(0,s.createElement)("span",{onClick:async e=>{let s;if(window.location.href="#statistics","goal_id"===t?(E[a]&&E[a].goal_specific_page&&E[a].goal_specific_page.value?(p("page_url",E[a].goal_specific_page.value),p(t,a),u.toast.info((0,o.__)("Filtering by goal & goal specific page","burst-statistics"))):(p(t,a),u.toast.info((0,o.__)("Filtering by goal","burst-statistics"))),D.includes("conversions")||v([...D,"conversions"])):(p(t,""),await new Promise((e=>setTimeout(e,10))),p(t,a,!0),await(async e=>{const t=document.querySelector(".burst-data-filter--animate"),a=window.getComputedStyle(t),s=t.offsetParent?t.offsetParent.offsetLeft:0,r=t.offsetParent?t.offsetParent.offsetTop:0,l=parseInt(a.marginLeft),n=parseInt(a.marginTop),i=t.offsetWidth,c=t.offsetHeight,o=e.clientX-i+window.scrollX-s-l,u=e.clientY-4*c+window.scrollY-r-n;t.style.transformOrigin="50% 50%",t.style.opacity=0,t.style.transform=`translateX(${o}px) translateY(${u}px)`,await new Promise((e=>setTimeout(e,50))),t.style.transition="transform 0.2s ease, opacity 0.2s ease-out",t.style.transform=`translateX(${o}px) translateY(${u}px) scale(1)`,t.style.opacity=1,t.style.transition="transform 0.5s ease-in-out, opacity 0.2s ease-out",t.style.transform="translateX(0) translateY(0)"})(e),y(!1)),g&&("number"==typeof g||Date.parse(g)>=16409952e5)&&(s="number"==typeof g?(0,m.J7)(g):g),s){const e=_?_>0?(0,m.J7)(_):_:(0,d.default)(new Date,"yyyy-MM-dd");h(s),w(e),N("custom")}},className:"burst-click-to-filter"},b))}},5428:(e,t,a)=>{a.d(t,{Z:()=>r});var s=a(9307);const r=e=>{const{className:t,title:a,controls:r,children:l,footer:n}=e;return(0,s.createElement)("div",{className:"burst-grid-item "+t},(0,s.createElement)("div",{className:"burst-grid-item-header"},(0,s.createElement)("h3",{className:"burst-grid-title burst-h4"},a),(0,s.createElement)("div",{className:"burst-grid-item-controls"},r)),(0,s.createElement)("div",{className:"burst-grid-item-content"},l),(0,s.createElement)("div",{className:"burst-grid-item-footer"},n))}},5910:(e,t,a)=>{a.r(t),a.d(t,{default:()=>G});var s=a(9307);const r=e=>{const{children:t}=e;return(0,s.createElement)("div",{className:"burst-data-filters"},t)};var l=a(9196),n=a(5736),i=a(5428),c=a(3882),o=a(5609),u=a(2825),d=a(1491);const m=e=>{let{metrics:t,filters:a}=e;const[r,i]=(0,l.useState)(null),m=(0,d.B)((e=>e.setMetrics)),[f,b]=(0,l.useState)(t);let g={visitors:(0,n.__)("Unique visitors","burst-statistics"),pageviews:(0,n.__)("Pageviews","burst-statistics"),bounces:(0,n.__)("Bounces","burst-statistics"),sessions:(0,n.__)("Sessions","burst-statistics")};const _=Boolean(r);a.goal_id>0&&(g.conversions=(0,n.__)("Conversions","burst-statistics"));const p=e=>{m(f),e.preventDefault(),i(null)},y=(0,l.useCallback)((e=>{const t=e.target.value,a=e.target.checked;b((e=>a?[...e,t]:e.filter((e=>e!==t))))}),[]);return(0,s.createElement)("div",null,(0,s.createElement)(o.Button,{id:"burst-filter-button",className:"burst-filter-button"+(_?" active":""),"aria-controls":"burst-filter-menu","aria-haspopup":"true","aria-expanded":_?"true":void 0,onClick:e=>{i(e.currentTarget)}},(0,s.createElement)(c.Z,{name:"filter"})),(0,s.createElement)(u.ZP,{id:"burst-filter-menu",className:"burst burst-filter-menu",anchorEl:r,anchorOrigin:{vertical:"bottom",horizontal:"right"},transformOrigin:{vertical:"top",horizontal:"right"},open:_,onClose:p},(0,s.createElement)("h4",null,(0,n.__)("Select metrics","burst-statistics")),Object.keys(g).map((e=>(0,s.createElement)("div",{className:"burst-filter-dropdown-content-body-item",key:e},(0,s.createElement)("label",null,(0,s.createElement)("input",{type:"checkbox",value:e,checked:f.includes(e),onChange:y}),g[e])))),(0,s.createElement)("input",{type:"hidden",name:"burst-metrics",value:t}),(0,s.createElement)(o.Button,{onClick:p,className:"burst-button burst-button--secondary"},(0,n.__)("Apply","burst-statistics"))))},f=e=>{let{filters:t}=e;const r=(0,d.B)((e=>e.data)),c=(0,d.B)((e=>e.metrics)),o=(0,d.B)((e=>e.loading)),[u,f]=(0,l.useState)(null);return(0,l.useEffect)((()=>{u||Promise.all([a.e(376),a.e(354)]).then(a.bind(a,9354)).then((e=>{let{default:t}=e;f((()=>t))}))}),[]),(0,s.createElement)(i.Z,{className:"burst-column-2",title:(0,n.__)("Insights","burst-statistics"),controls:(0,s.createElement)(m,{metrics:c,filters:t})},r&&u&&(0,s.createElement)(u,{loading:o,data:r}))};var b=a(5417),g=a(8197),_=a(4046);const p=e=>{let{iconKey:t,title:a,subtitle:r,value:l,change:n,changeStatus:i}=e;return(0,s.createElement)("div",{className:"block__explanation-and-stats"},(0,s.createElement)(c.Z,{name:t}),(0,s.createElement)("div",{className:"block__explanation-and-stats__left"},(0,s.createElement)("h3",{className:"burst-h5"},a),(0,s.createElement)("p",null,r)),(0,s.createElement)("div",{className:"block__explanation-and-stats__right"},(0,s.createElement)("span",{className:"burst-h4"},l),(0,s.createElement)("p",{className:"uplift "+i},n)))};var y=a(3855),E=a(7803);const v=e=>{let{startDate:t,endDate:a}=e;const r=(0,y.Z)(t),l=(0,y.Z)(a),i=(0,E.default)(l,r)+1,c=(1===i?(0,n.__)("vs. previous day","burst-statistics"):(0,n.__)("vs. previous %s days","burst-statistics")).replace("%s",i);return(0,s.createElement)(s.Fragment,null,(0,s.createElement)("p",{className:"burst-small-text"},c))},D=e=>{const{startDate:t,endDate:a}=e,{data:r,loading:l}=(0,b.C)((e=>({data:e.data,loading:e.loading})));let c=l?"burst-loading":"";return(0,s.createElement)(i.Z,{title:(0,n.__)("Compare","burst-statistics"),footer:(0,s.createElement)(v,{startDate:t,endDate:a})},(0,s.createElement)("div",{className:"burst-loading-container "+c},Object.keys(r).map(((e,t)=>{let a=r[e];return(0,s.createElement)(p,{key:t,iconKey:e,title:a.title,subtitle:a.subtitle,value:a.value,change:a.change,changeStatus:a.changeStatus})}))))};a(8399),a(8518);var h=a(9324),w=a(7333);const N=()=>{const e=(0,h.d)((e=>e.loading)),t=(0,h.d)((e=>e.data));let a=e?"burst-loading":"";return(0,s.createElement)(i.Z,{title:(0,n.__)("Devices","burst-statistics")},(0,s.createElement)("div",{className:"burst-loading-container "+a},Object.keys(t).map(((e,a)=>{let r=t[e];return(0,s.createElement)(w.Z,{key:e,filter:"device",filterValue:e,label:r.title},(0,s.createElement)(p,{iconKey:e,title:r.title,subtitle:r.subtitle,value:r.value,change:r.change,changeStatus:r.changeStatus}))}))))};var k=a(44);const P=e=>(0,s.createElement)("div",{className:"burst-empty-data-table"},(0,s.createElement)("p",{className:"burst-small-text"},(0,n.__)("No data available in table","burst-statistics")));var Z=a(2501);const C=()=>{const e=(0,Z.T)((e=>e.loading)),t=(0,Z.T)((e=>e.data)),a=e?"burst-loading":"";if(0!==t.length){const e=e=>(0,s.createElement)(w.Z,{filter:"page_url",filterValue:e.page_url},decodeURI(e.page_url));t.columns[0].cell=e}let r=t.data,l=t.columns;return(0,s.createElement)(i.Z,{className:"burst-column-2 border-to-border datatable",title:(0,n.__)("Per page","burst-statistics")},(0,s.createElement)("div",{className:`burst-loading-container ${a}`},(0,s.createElement)(k.default,{columns:l,data:r,defaultSortFieldId:2,defaultSortAsc:!1,pagination:!0,paginationRowsPerPageOptions:[10,25,50,100,200],paginationPerPage:10,paginationComponentOptions:{rowsPerPageText:"",rangeSeparatorText:(0,n.__)("of","burst-statistics"),noRowsPerPage:!1,selectAllRowsItem:!0,selectAllRowsItemText:(0,n.__)("All","burst-statistics")},noDataComponent:(0,s.createElement)(P,null)})))};var S=a(4222);const M=e=>{const t=(0,S.T)((e=>e.loading)),a=(0,S.T)((e=>e.data));let r=t?"burst-loading":"";0!==a.length&&(a.columns[0].cell=e=>(0,s.createElement)(w.Z,{filter:"referrer",filterValue:e.referrer},decodeURI(e.referrer)));let l=a.data,c=a.columns;return(0,s.createElement)(i.Z,{className:"burst-column-2 border-to-border datatable",title:(0,n.__)("Acquisition","burst-statistics")},(0,s.createElement)("div",{className:"burst-loading-container "+r},(0,s.createElement)(k.default,{columns:c,data:l,defaultSortFieldId:2,defaultSortAsc:!1,pagination:!0,paginationRowsPerPageOptions:[10,25,50,100,200],paginationPerPage:10,paginationComponentOptions:{rowsPerPageText:"",rangeSeparatorText:(0,n.__)("of","burst-statistics"),noRowsPerPage:!1,selectAllRowsItem:!0,selectAllRowsItemText:(0,n.__)("All","burst-statistics")},noDataComponent:(0,s.createElement)(P,null)})))};var T=a(7429);const x=()=>{const e=(0,g.y)((e=>e.filters)),t=(0,g.y)((e=>e.filtersConf)),a=(0,g.y)((e=>e.setFilters)),r=(0,T.q)((e=>e.goalFields)),l=(0,g.y)((e=>e.animate)),n=(0,d.B)((e=>e.setMetrics)),i=(0,d.B)((e=>e.metrics));let o="";const u=e=>{let t="burst-data-filter";return l===e&&(t+=" burst-data-filter--animate"),t};return(0,s.createElement)(s.Fragment,null,Object.keys(t).map(((l,d)=>{if(""!==e[l])return"goal_id"===l?(m=e[l],o=r[m]?r[m].goal_title.value:""):o=e[l],""!==t[l].icon&&t[l].icon,(0,s.createElement)("div",{className:u(l),key:d},(0,s.createElement)(c.Z,{name:t[l].icon,size:"16"}),(0,s.createElement)("p",{className:"burst-data-filter__label"},t[l].label),(0,s.createElement)("span",{className:"burst-data-filter-divider"}),(0,s.createElement)("p",{className:"burst-data-filter__value"},decodeURI(o)),(0,s.createElement)("button",{onClick:()=>(e=>{a(e,""),"goal_id"===e&&n(i.filter((e=>"conversions"!==e)))})(l)},(0,s.createElement)(c.Z,{name:"times",color:"var(--rsp-grey-500)",size:"16"})));var m})))};var Y=a(286),O=a(3151),R=a(9119),B=a(3894),F=a(7349),I=a(8054),A=a(1640),z=a(4135),L=a(8148),j=a(1593),q=a(876),X=a(9546);const $=new Date,U=-60*$.getTimezoneOffset(),V=Math.floor($.getTime()/1e3)+3600*burst_settings.gmt_offset-U,K=new Date(1e3*V);function J(e){const t=this.range();return(0,O.default)(e.startDate,t.startDate)&&(0,O.default)(e.endDate,t.endDate)}const W={today:{label:(0,n.__)("Today","burst-statistics"),range:()=>({startDate:(0,R.default)(K),endDate:(0,B.default)(K)})},yesterday:{label:(0,n.__)("Yesterday","burst-statistics"),range:()=>({startDate:(0,R.default)((0,F.default)(K,-1)),endDate:(0,B.default)((0,F.default)(K,-1))})},"last-7-days":{label:(0,n.__)("Last 7 days","burst-statistics"),range:()=>({startDate:(0,R.default)((0,F.default)(K,-7)),endDate:(0,B.default)((0,F.default)(K,-1))})},"last-30-days":{label:(0,n.__)("Last 30 days","burst-statistics"),range:()=>({startDate:(0,R.default)((0,F.default)(K,-30)),endDate:(0,B.default)((0,F.default)(K,-1))})},"last-90-days":{label:(0,n.__)("Last 90 days","burst-statistics"),range:()=>({startDate:(0,R.default)((0,F.default)(K,-90)),endDate:(0,B.default)((0,F.default)(K,-1))})},"last-month":{label:(0,n.__)("Last month","burst-statistics"),range:()=>({startDate:(0,I.default)((0,A.default)(K,-1)),endDate:(0,z.default)((0,A.default)(K,-1))})},"week-to-date":{label:(0,n.__)("Week to date","burst-statistics"),range:()=>({startDate:(0,R.default)((0,F.default)(K,-K.getDay())),endDate:(0,B.default)(K)})},"month-to-date":{label:(0,n.__)("Month to date","burst-statistics"),range:()=>({startDate:(0,I.default)(K),endDate:(0,B.default)(K)})},"year-to-date":{label:(0,n.__)("Year to date","burst-statistics"),range:()=>({startDate:(0,L.Z)(K),endDate:(0,B.default)(K)})},"last-year":{label:(0,n.__)("Last year","burst-statistics"),range:()=>({startDate:(0,L.Z)((0,j.default)(K,-1)),endDate:(0,q.Z)((0,j.default)(K,-1))})}},H=e=>{const[t,a]=(0,l.useState)(null),r=Boolean(t),n=(0,_.Y)((e=>e.startDate)),i=(0,_.Y)((e=>e.endDate)),o=(0,_.Y)((e=>e.setStartDate)),d=(0,_.Y)((e=>e.setEndDate)),m=(0,_.Y)((e=>e.range)),f=(0,_.Y)((e=>e.setRange)),[b,g]=(0,l.useState)((0,y.Z)(n)),[p,E]=(0,l.useState)((0,y.Z)(i)),v=(0,l.useRef)(0),D=burst_settings.date_ranges,h=(0,l.useMemo)((()=>Object.values(D).filter(Boolean).map((e=>{const t=W[e];return t.isSelected=J,t}))),[D]),w=(0,l.useCallback)((e=>{a(e.currentTarget)}),[]),N=(0,l.useCallback)((()=>{a(null)}),[]),k=(0,l.useCallback)((e=>{v.current++;const{startDate:t,endDate:a}=e.selection,s=(0,X.default)(t,"yyyy-MM-dd"),r=(0,X.default)(a,"yyyy-MM-dd");g((0,y.Z)(s)),E((0,y.Z)(r));const l=Object.keys(W).find((t=>W[t].isSelected(e.selection)))||"custom";(2===v.current||"custom"!==l||s!==r)&&(v.current=0,o(s),d(r),f(l),N())}),[o,d,f,N]),P="MMMM d, yyyy",Z=n?(0,X.default)(new Date(n),P):(0,X.default)(defaultStart,P),C=i?(0,X.default)(new Date(i),P):(0,X.default)(defaultEnd,P),S={startDate:b,endDate:p,key:"selection"};return(0,s.createElement)("div",{className:"burst-date-range-container"},(0,s.createElement)("button",{onClick:w,id:"burst-date-range-picker-open-button"},(0,s.createElement)(c.Z,{name:"calendar",size:"18"}),"custom"===m&&Z+" - "+C,"custom"!==m&&W[m].label,(0,s.createElement)(c.Z,{name:"chevron-down"})),(0,s.createElement)(u.ZP,{anchorEl:t,anchorOrigin:{vertical:"bottom",horizontal:"right"},transformOrigin:{vertical:"top",horizontal:"right"},open:r,onClose:N,className:"burst"},(0,s.createElement)("div",{id:"burst-date-range-picker-container"},(0,s.createElement)(Y.Dw,{ranges:[S],rangeColors:["var(--rsp-brand-primary)"],dateDisplayFormat:P,monthDisplayFormat:"MMMM",onChange:e=>{k(e)},inputRanges:[],showSelectionPreview:!0,months:2,direction:"horizontal",minDate:new Date(2022,0,1),maxDate:K,staticRanges:h}))))},G=()=>{const{filters:e}=(0,g.y)((e=>({filters:e.filters}))),{startDate:t,endDate:a,range:l}=(0,_.Y)((e=>({startDate:e.startDate,endDate:e.endDate,range:e.range}))),n={filters:e,startDate:t,endDate:a,range:l};return(0,s.createElement)("div",{className:"burst-content-area burst-grid burst-statistics"},(0,s.createElement)(r,null,(0,s.createElement)(x,null),(0,s.createElement)(H,null)),(0,s.createElement)(f,{filters:e}),(0,s.createElement)(D,n),(0,s.createElement)(N,n),(0,s.createElement)(C,n),(0,s.createElement)(M,n))}}}]);
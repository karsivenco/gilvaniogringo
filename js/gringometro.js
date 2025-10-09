
// Gringômetro — renderização SVG + animação
// Use: inclua este arquivo após o SVG <svg id="grafico-gringometro"></svg>
(function() {
  const AZUL_TX = "#005075";   // texto
  const AMARELO = "#fdc303";   // barra "Abertos"
  const TOTAL_ABERTOS    = 1121;
  const TOTAL_CONCLUIDOS = 813;

  const dadosMensais = [
    { mes: "Jan", valor: 316 },
    { mes: "Fev", valor: 124 },
    { mes: "Mar", valor: 49  },
    { mes: "Abr", valor: 47  },
    { mes: "Mai", valor: 31  },
    { mes: "Jun", valor: 121 },
    { mes: "Jul", valor: 29  },
    { mes: "Ago", valor: 53  },
    { mes: "Set", valor: 43  }
  ];

  const tonsAzuis = ["#bfe8fb","#a6e1fa","#8fd7f7","#6ec8f0","#4fb8eb","#2798d0","#0f7fb9","#006aa1","#004f7a"];

  function render() {
    const svg = document.getElementById("grafico-gringometro");
    if (!svg) return;

    const W = 650, H = 230;
    const M = { top: 12, left: 120, right: 70, rowGap: 56 };
    const barH = 26, rx = 6;
    const barW = W - M.left - M.right;

    svg.setAttribute("viewBox", `0 0 ${W} ${H}`);
    svg.innerHTML = "";

    const el = (n, a={}) => {
      const e = document.createElementNS("http://www.w3.org/2000/svg", n);
      for (const k in a) e.setAttribute(k, a[k]);
      return e;
    };
    const add = (p,c) => (p.appendChild(c), c);

    const y1 = M.top + 12;
    const y2 = y1 + barH + M.rowGap;

    add(svg, el("text",{x:20,y:y1+barH-8,"font-size":14,"font-weight":800,fill:AZUL_TX})).textContent="Abertos";
    add(svg, el("text",{x:20,y:y2+barH-8,"font-size":14,"font-weight":800,fill:AZUL_TX})).textContent="Concluídos";

    add(svg, el("rect",{x:M.left,y:y1,width:barW,height:barH,rx,fill:AMARELO}));
    add(svg, el("text",{x:M.left+barW+10,y:y1+barH-7,"font-size":16,"font-weight":800,fill:AZUL_TX})).textContent=TOTAL_ABERTOS.toLocaleString("pt-BR");

    add(svg, el("rect",{x:M.left,y:y2,width:barW,height:barH,rx,fill:"#e9eef3"}));
    add(svg, el("text",{x:M.left+barW+10,y:y2+barH-7,"font-size":16,"font-weight":800,fill:AZUL_TX})).textContent=TOTAL_CONCLUIDOS.toLocaleString("pt-BR");

    const gap=2; let cursorX=M.left; const segmentos=[];
    dadosMensais.forEach((d,i)=>{
      const fra=d.valor/TOTAL_CONCLUIDOS;
      const w=Math.max(8, Math.round(barW*fra)-gap);
      const isFirst=i===0, isLast=i===dadosMensais.length-1;

      const rect=add(svg, el("rect",{x:cursorX,y:y2,width:0,height:barH,rx:isFirst||isLast?6:0,fill:tonsAzuis[i%tonsAzuis.length]}));
      segmentos.push({rect:rect,targetW:w,startX:cursorX,mes:d.mes});

      const labelX=cursorX+w/2;
      add(svg, el("text",{x:labelX,y:y2+barH+16,"font-size":10,"text-anchor":"middle",fill:"#6b7b86","font-weight":600})).textContent=d.mes;

      cursorX+=w+gap;
    });

    const ticker=add(svg, el("text",{x:W/2,y:y2+barH+38,"text-anchor":"middle","font-size":12,fill:AZUL_TX,"font-weight":800}));
    ticker.textContent=" ";

    const DUR_SEG=600, INTERVAL=150;
    function animateSegment(idx){
      if(idx>=segmentos.length){ ticker.textContent=" "; return; }
      const {rect,targetW,startX,mes}=segmentos[idx];
      ticker.textContent=mes;
      const t0=performance.now();
      function step(t){
        const p=Math.min(1,(t-t0)/DUR_SEG);
        rect.setAttribute("width",(targetW*p).toFixed(2));
        rect.setAttribute("x",startX);
        if(p<1) requestAnimationFrame(step);
        else setTimeout(()=>animateSegment(idx+1), INTERVAL);
      }
      requestAnimationFrame(step);
    }
    animateSegment(0);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", render);
  } else {
    render();
  }
})();

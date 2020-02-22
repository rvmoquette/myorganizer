
function strip_tags(html)
{
    //PROCESS STRING
    if(arguments.length < 3)
    {
        html=html.replace(/<\/?(?!\!)[^>]*>/gi, '');
    }
    else
    {
        var allowed = arguments[1];
        var specified = eval("["+arguments[2]+"]" );
        if(allowed)
        {
            var regex='</?(?!(' + specified.join('|') + '))\b[^>]*>';
            html=html.replace(new RegExp(regex, 'gi'), '');
        }
        else
        {
            var regex='</?(' + specified.join('|') + ')\b[^>]*>';
            html=html.replace(new RegExp(regex, 'gi'), '');
        }
    }
    //CHANGE NAME TO CLEAN JUST BECAUSE
    var clean_string = html;
    //RETURN THE CLEAN STRING
    return clean_string;
}

function isNumber(n)
{
    return !isNaN(parseFloat(n)) && isFinite(n);
}

var pt = new RegExp(' pt|%', 'gi');

function DESC(a,b)
{
    a=strip_tags(a[1]);
    b=strip_tags(b[1]);
    a=a.toLowerCase();
    b=b.toLowerCase();
    a = a.replace(pt, '');
    b = b.replace(pt, '');
    a = a.replace(",", '.');
    b = b.replace(",", '.');
    if (isNumber(a) && isNumber(b))
    {
        a = parseFloat(a);
        b = parseFloat(b);
    }
    if(a > b)
        return -1;
    if(a < b)
        return 1;
    return 0;
}

function ASC(a,b)
{
    a=strip_tags(a[1]);
    b=strip_tags(b[1]);
    a=a.toLowerCase();
    b=b.toLowerCase();
    a = a.replace(pt, '');
    b = b.replace(pt, '');
    a = a.replace(",", '.');
    b = b.replace(",", '.');
    if (isNumber(a) && isNumber(b))
    {
        a = parseFloat(a);
        b = parseFloat(b);
    }
    if(a > b)
        return 1;
    if(a < b)
        return -1;
    return 0;
}

function sortTable(tid, col, ord)
{
    mybody=document.getElementById(tid).getElementsByTagName('tbody')[0];
    lines=mybody.getElementsByTagName('tr');
    var sorter=new Array();
    sorter.length=0;
    var i=-1;
    while(lines[++i])
    {
        sorter.push([lines[i],lines[i].getElementsByTagName('td')[col].innerHTML]);
    }
    sorter.sort(ord);
    j=-1;
    var paire=1;
    while(sorter[++j])
    {
        temp = sorter[j][0].className;
        temp = temp.replace("odd", "");
        temp = temp.replace("  ", " " );
        if (paire!=1)
        {
            temp+=" odd ";
        }
        sorter[j][0].className = temp;
        paire=-paire;
        mybody.appendChild(sorter[j][0]);
    }
}

var sens=1;
var derniere_colonne_triee=0;

function trier_colonne_n(tid, colonne)
{
    if ( derniere_colonne_triee != colonne )
    {
        derniere_colonne_triee = colonne;
        sens = 1;
    }
    if (sens==1)
    {
        sortTable(tid,colonne,ASC);
        sens = 2;
    }
    else
    {
       sortTable(tid,colonne,DESC);
       sens = 1;
    }
    recherche_tableau(tid);
}

//###################################################################

function recherche_tableau(tid)
{
    mybody=document.getElementById(tid).getElementsByTagName('tbody')[0];
    lines=mybody.getElementsByTagName('tr');

    var recherche = "";
    var l = 0;
    var v = "";

    var masquer=0;

    var sorter=new Array();
    sorter.length=0;
    var i=-1;
    var j=-1;

    while(lines[++i])
    {

        masquer = 0;

        j=-1;
        while(lines[i].getElementsByTagName('td')[++j])
        {
            recherche = document.getElementById(tid + "_" + j).value;
            if (recherche!="")
            {
                recherche=recherche.toLowerCase();
                v = lines[i].getElementsByTagName('td')[j].innerHTML;
                v = strip_tags(v);
                v = v.toLowerCase();
                if ( v.indexOf(recherche)==-1 )
                {
                    masquer = 1;
                }
            }
        }

        sorter.push([lines[i],masquer]);

    }

    var paire=1;
    j=-1;
    while(sorter[++j])
    {
        temp = sorter[j][0].className;
        temp = temp.replace("odd", "");
        temp = temp.replace("masquer", "");
        temp = temp.replace("  ", " " );
        if (paire!=1)
            temp+=" odd ";
        if (sorter[j][1]==1)
            temp+=" masquer ";
        else
            paire=-paire;
        sorter[j][0].className = temp;
        mybody.appendChild(sorter[j][0]);
    }

}

function afficher_ligne_tri(tid)
{
    var id_ligne = "ligne_recherche_" + tid
    if (document.getElementById(id_ligne).className == "ligne_recherche")
    {
        document.getElementById(id_ligne).className = "masquer";

        var colonnes=document.getElementById(tid).getElementsByTagName('tbody')[0].getElementsByTagName('tr')[0].getElementsByTagName('td');

        var i=-1;
        while(colonnes[++i])
        {
            document.getElementById(tid + "_" + i).value="";
        }
        recherche_tableau(tid);
    }
    else
    {
        document.getElementById(id_ligne).className = "ligne_recherche";
    }
}

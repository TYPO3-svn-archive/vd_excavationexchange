/*
 * Category material and associated description context switch
 */
function refresh_categoryMaterial(lot, category)
{
	
	var designiationuscsmeuble=document.getElementById("designiationuscsmeuble_"+lot);
	var designiationsimplemeuble=document.getElementById("designiationsimplemeuble_"+lot);    
	var designiationsimplemeubleha=document.getElementById("designiationsimplemeubleha_"+lot);
	var designiationsimplemeublehb=document.getElementById("designiationsimplemeublehb_"+lot);
	var designiationroche=document.getElementById("designiationroche_"+lot);
	

	if(category == 1){
		/* hide shows designations */
		designiationuscsmeuble.style.display = 'none';
		designiationsimplemeuble.style.display = 'none';
		designiationsimplemeubleha.style.display = 'block';
		designiationsimplemeublehb.style.display = 'none';
		designiationroche.style.display = 'none';
	}else if(category == 2){
		designiationuscsmeuble.style.display = 'none';
		designiationsimplemeuble.style.display = 'none';
		designiationsimplemeubleha.style.display = 'none';
		designiationsimplemeublehb.style.display = 'block';
		designiationroche.style.display = 'none';
	}else if(category == 3){
		designiationuscsmeuble.style.display = 'none';
		designiationsimplemeuble.style.display = 'block';
		designiationsimplemeubleha.style.display = 'none';
		designiationsimplemeublehb.style.display = 'none';
		designiationroche.style.display = 'none';
	}else if(category == 4){
		designiationuscsmeuble.style.display = 'none';
		designiationsimplemeuble.style.display = 'none';
		designiationsimplemeubleha.style.display = 'none';
		designiationsimplemeublehb.style.display = 'none';
		designiationroche.style.display = 'block';		
	}else{
		designiationuscsmeuble.style.display = 'none';
		designiationsimplemeuble.style.display = 'none';
		designiationsimplemeubleha.style.display = 'none';
		designiationsimplemeublehb.style.display = 'none';
		designiationroche.style.display = 'none';
	}
	
	return true;
	
}
/*
 * Offre form display add lot form
 */
function offerForm_showAddLot(id)
{
	var obj = document.getElementById(id);
	obj.style.display = 'block';
	return true;
	
}
 
/**
 * Offre form display add lot form
 */
function actionLinkMouseOver(id)
{
	var obj = document.getElementById(id);
	obj.style.textDecoration = "underline";
	return true;
	
}
function actionLinkMouseOut(id)
{
	var obj = document.getElementById(id);
	obj.style.textDecoration = 'none';
	return true;
	
}
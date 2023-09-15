//定义利率
rateArray = new Array;          
//2004年之前的旧利率
rateArray[1] = new Array;
rateArray[1][1] = new Array;
rateArray[1][2] = new Array;
rateArray[1][1][5] = 0.0477;//商贷 1～5年 4.77%
rateArray[1][1][10] = 0.0504;//商贷 5-30年 5.04%
rateArray[1][2][5] = 0.0360;//公积金 1～5年 3.60%
rateArray[1][2][10] = 0.0405;//公积金 5-30年 4.05%

//2005年	1月的新利率
rateArray[2] = new Array;
rateArray[2][1] = new Array;
rateArray[2][2] = new Array;
rateArray[2][1][5] = 0.0495;//商贷 1～5年 4.95%
rateArray[2][1][10] = 0.0531;//商贷 5-30年 5.31%
rateArray[2][2][5] = 0.0378;//公积金 1～5年 3.78%
rateArray[2][2][10] = 0.0423;//公积金 5-30年 4.23%

//2006年	1月的新利率下限
rateArray[3] = new Array;
rateArray[3][1] = new Array;
rateArray[3][2] = new Array;
rateArray[3][1][5] = 0.0527;//商贷 1～5年 5.27%
rateArray[3][1][10] = 0.0551;//商贷 5-30年 5.51%
rateArray[3][2][5] = 0.0396;//公积金 1～5年 3.96%
rateArray[3][2][10] = 0.0441;//公积金 5-30年 4.41%
			
//2006年	1月的新利率上限
rateArray[4] = new Array;
rateArray[4][1] = new Array;
rateArray[4][2] = new Array;
rateArray[4][1][5] = 0.0527;//商贷 1～5年 5.27%
rateArray[4][1][10] = 0.0612;//商贷 5-30年 6.12%
rateArray[4][2][5] =  0.0396;//公积金 1～5年 3.96%
rateArray[4][2][10] = 0.0441;//公积金 5-30年 4.41%

//2006年	4月28日的新利率下限
rateArray[5] = new Array;
rateArray[5][1] = new Array;
rateArray[5][2] = new Array;
rateArray[5][1][5] = 0.0551;//商贷 1～5年 5.51%
rateArray[5][1][10] = 0.0575;//商贷 5-30年 5.75%
rateArray[5][2][5] =  0.0414;//公积金 1～5年 4.14%
rateArray[5][2][10] = 0.0459;//公积金 5-30年 4.59%

//2006年	4月28日的新利率上限
rateArray[6] = new Array;
rateArray[6][1] = new Array;
rateArray[6][2] = new Array;
rateArray[6][1][5] = 0.0612;//商贷 1～5年 6.12%
rateArray[6][1][10] = 0.0639;//商贷 5-30年 6.39%
rateArray[6][2][5] =  0.0414;//公积金 1～5年 4.14%
rateArray[6][2][10] = 0.0459;//公积金 5-30年 4.59%

//2006年	8月19日的新利率下限
rateArray[7] = new Array;
rateArray[7][1] = new Array;
rateArray[7][2] = new Array;
rateArray[7][1][5] = 0.0551;//商贷 1～5年 5.51%
rateArray[7][1][10] = 0.0581;//商贷 5-30年 5.81%
rateArray[7][2][5] =  0.0414;//公积金 1～5年 4.14%
rateArray[7][2][10] = 0.0459;//公积金 5-30年 4.59%

//2006年	8月19日的新利率上限
rateArray[8] = new Array;
rateArray[8][1] = new Array;
rateArray[8][2] = new Array;
rateArray[8][1][5] = 0.0648;//商贷 1～5年 6.48%
rateArray[8][1][10] = 0.0684;//商贷 5-30年 6.84%
rateArray[8][2][5] =  0.0414;//公积金 1～5年 4.14%
rateArray[8][2][10] = 0.0459;//公积金 5-30年 4.59%


//2007年	3月18日的新利率下限
rateArray[9] = new Array;
rateArray[9][1] = new Array;
rateArray[9][2] = new Array;
rateArray[9][1][5] = 0.0574;//商贷 1～5年 5.74%
rateArray[9][1][10] = 0.0604;//商贷 5-30年 6.04%
rateArray[9][2][5] = 0.0432;//公积金 1～5年 4.32%
rateArray[9][2][10] = 0.0477;//公积金 5-30年 4.77%

//2007年	3月18日的新利率上限
rateArray[10] = new Array;
rateArray[10][1] = new Array;
rateArray[10][2] = new Array;
rateArray[10][1][5] = 0.0675;//商贷 1～5年 6.75%
rateArray[10][1][10] = 0.0711;//商贷 5-30年 7.11%
rateArray[10][2][5] = 0.0432;//公积金 1～5年 4.32%
rateArray[10][2][10] = 0.0477;//公积金 5-30年 4.77%


//2007年	5月19日的新利率下限
rateArray[11] = new Array;
rateArray[11][1] = new Array;
rateArray[11][2] = new Array;
rateArray[11][1][5] = 0.0589;//商贷 1～5年 5.89%
rateArray[11][1][10] = 0.0612;//商贷 5-30年 6.12%
rateArray[11][2][5] = 0.0441;//公积金 1～5年 4.41%%
rateArray[11][2][10] = 0.0486;//公积金 5-30年 4.86%%

//2007年	5月19日的新利率上限
rateArray[12] = new Array;
rateArray[12][1] = new Array;
rateArray[12][2] = new Array;
rateArray[12][1][5] = 0.0693;//商贷 1～5年 6.93%
rateArray[12][1][10] = 0.0720;//商贷 5-30年 7.20%
rateArray[12][2][5] = 0.0441;//公积金 1～5年 4.41%%
rateArray[12][2][10] = 0.0486;//公积金 5-30年 4.86%%

//2007年	7月21日的新利率下限
rateArray[13] = new Array;
rateArray[13][1] = new Array;
rateArray[13][2] = new Array;
rateArray[13][1][5] = 0.0612;//商贷 1～5年 6.12%
rateArray[13][1][10] = 0.06273;//商贷 5-30年 6.273%
rateArray[13][2][5] = 0.0450;//公积金 1～5年 4.50%%
rateArray[13][2][10] = 0.0495;//公积金 5-30年 4.95%%

//2007年	7月21日的新利率上限
rateArray[14] = new Array;
rateArray[14][1] = new Array;
rateArray[14][2] = new Array;
rateArray[14][1][5] = 0.0720;//商贷 1～5年 7.20%
rateArray[14][1][10] = 0.0738;//商贷 5-30年 7.38%
rateArray[14][2][5] = 0.0450;//公积金 1～5年 4.50%%
rateArray[14][2][10] = 0.0495;//公积金 5-30年 4.95%%

//2007年	8月22日的新利率下限
rateArray[15] = new Array;
rateArray[15][1] = new Array;
rateArray[15][2] = new Array;
rateArray[15][1][5] = 0.06273;//商贷 1～5年 6.273%
rateArray[15][1][10] = 0.06426;//商贷 5-30年 6.426%
rateArray[15][2][5] = 0.0459;//公积金 1～5年 4.59%
rateArray[15][2][10] = 0.0504;//公积金 5-30年 5.04%

//2007年	8月22日的新利率上限
rateArray[16] = new Array;
rateArray[16][1] = new Array;
rateArray[16][2] = new Array;
rateArray[16][1][5] = 0.0738;//商贷 1～5年 7.38%
rateArray[16][1][10] = 0.0756;//商贷 5-30年 7.56%
rateArray[16][2][5] = 0.0459;//公积金 1～5年 4.59%
rateArray[16][2][10] = 0.0504;//公积金 5-30年 5.04%

//2007年	9月15日的新利率下限
rateArray[17] = new Array;
rateArray[17][1] = new Array;
rateArray[17][2] = new Array;
rateArray[17][1][5] = 0.06503;//商贷 1～5年 6.503%
rateArray[17][1][10] = 0.06656;//商贷 5-30年 6.656%
rateArray[17][2][5] = 0.0477;//公积金 1～5年 4.77%
rateArray[17][2][10] = 0.0522;//公积金 5-30年 5.22%

//2007年	9月15日的新利率上限
rateArray[18] = new Array;
rateArray[18][1] = new Array;
rateArray[18][2] = new Array;
rateArray[18][1][5] = 0.0765;//商贷 1～5年 7.65%
rateArray[18][1][10] = 0.0783;//商贷 5-30年 7.83%
rateArray[18][2][5] = 0.0477;//公积金 1～5年 4.77%
rateArray[18][2][10] = 0.0522;//公积金 5-30年 5.22%

//2007年	9月15日新利率(第二套房)
rateArray[19] = new Array;
rateArray[19][1] = new Array;
rateArray[19][2] = new Array;
rateArray[19][1][5] = 0.08415;//商贷 1～5年 8.415%
rateArray[19][1][10] = 0.08613;//商贷 5-30年 8.613%
rateArray[19][2][5] = 0.0477;//公积金 1～5年 4.77%
rateArray[19][2][10] = 0.0522;//公积金 5-30年 5.22%


//2007年	12月21日的新利率下限
rateArray[20] = new Array;
rateArray[20][1] = new Array;
rateArray[20][2] = new Array;
rateArray[20][1][5] = 0.06579;//商贷 1～5年 6.579%
rateArray[20][1][10] = 0.06656;//商贷 5-30年 6.656%
rateArray[20][2][5] = 0.0477;//公积金 1～5年 4.77%
rateArray[20][2][10] = 0.0522;//公积金 5-30年 5.22%

//2007年	12月21日的新利率上限
rateArray[21] = new Array;
rateArray[21][1] = new Array;
rateArray[21][2] = new Array;
rateArray[21][1][5] = 0.0774;//商贷 1～5年 7.74%
rateArray[21][1][10] = 0.0783;//商贷 5-30年 7.83%
rateArray[21][2][5] = 0.0477;//公积金 1～5年 4.77%
rateArray[21][2][10] = 0.0522;//公积金 5-30年 5.22%

//2007年	12月21日新利率(第二套房)
rateArray[22] = new Array;
rateArray[22][1] = new Array;
rateArray[22][2] = new Array;
rateArray[22][1][5] = 0.08514;//商贷 1～5年 8.514%
rateArray[22][1][10] = 0.08613;//商贷 5-30年 8.613%
rateArray[22][2][5] = 0.0477;//公积金 1～5年 4.77%
rateArray[22][2][10] = 0.0522;//公积金 5-30年 5.22%


//得到利率
function fGetRateData(lilv_class,type,years){
	var lilv_class = parseInt(lilv_class);
    if (years<=5){
		 return rateArray[lilv_class][type][5];
	}else{
		return rateArray[lilv_class][type][10];
	}
}

//本金还款的月还款额(参数: 年利率 / 贷款总额 / 贷款总月份 / 贷款当前月0～length-1)
function fGetMonthMoney2(lilv,total,month,cur_month){
	var lilv_month = lilv / 12;//月利率
	//return total * lilv_month * Math.pow(1 + lilv_month, month) / ( Math.pow(1 + lilv_month, month) -1 );
	var benjin_money = total/month;
	return (total - benjin_money * cur_month) * lilv_month + benjin_money;

}


//本息还款的月还款额(参数: 年利率/贷款总额/贷款总月份)
function fGetMonthMoney1(lilv,total,month){
	var lilv_month = lilv / 12;//月利率
	return total * lilv_month * Math.pow(1 + lilv_month, month) / ( Math.pow(1 + lilv_month, month) -1 );
}

/**
 * 获取贷款类别
 */
function fChooseType(){
	var type = $("type").value;
	if(type == 3){
		 $("accfundsTr").style.display = $("businessTr").style.display = "";
		 $("loansTr").style.display =  "none";
	}else{
		 $("accfundsTr").style.display = $("businessTr").style.display = "none";
		 $("loansTr").style.display =  "";
	}
	fChangeRate();
}

/**
 * 获取并设置利率
 */
function fChangeRate(){
	var ratetype = $("ratetype").value;
	var type = $("type").value;
	var years = $("years").value;
	if(type ==3){
		var businessRate  = fGetRateData(ratetype,1, years);//得到商贷利率
		var accfundsRate = fGetRateData(ratetype,2, years);//得到公积金利率
		$("businessRate").value = businessRate;
		$("accfundsRate").value = accfundsRate;
		return;
	}else{
		var rate = fGetRate(ratetype,type,years);
		$("rate").value = rate;
	}
}

/**
 * 获取利率
 * @param {number} ratetype 不同事情的利率
 * @param {number} type 贷款类型,1商业贷款，2公积金贷款
 * @param {number} years 贷款年限
 */
function fGetRate(ratetype,type,years){
	var ratetype = parseInt(ratetype);
	if (years<=5){
		 return rateArray[ratetype][type][5];
	}else{
		return rateArray[ratetype][type][10];
	}
}

/**
 * 清空输入
 */
function fReset(){
	$("business").value = $("accfunds").value = $("loans").value = "";
	fChangeRate();
}

function fShowResult(resultObj){
    if(resultObj.monthPay1){
        $("monthPayTr1").style.display = "";
        $("monthPayTr2").style.display = "none";
    }else{
        $("monthPayTr1").style.display = "none";
        $("monthPayTr2").style.display = "";
    }
    for(var o in resultObj){
        $(o).innerHTML = resultObj[o];
    }
}

function fGetResult()
{   
    var years    = $("years").value;
    var month    = years * 12;
    var type     = $("type").value;	
    var paytype  = $("paytype").value;
    var ratetype = $("ratetype").value;
    var result    = {};
    result.monthSpan  = month; // 支付月数

	if (type == 3){ // 组合贷款
        var businessRate  = fGetRateData(ratetype,1, years);//得到商贷利率
        var accfundsRate = fGetRateData(ratetype,2, years);//得到公积金利率
        var business = $("business").value;
        var accfunds = $("accfunds").value;
        if(accfunds.trim() == ""){
            alert("请输入公积金贷款金额");
            $("accfunds").focus();
            return false;
        }else if(!accfunds.isNumber()){
            alert("公积金贷款必须为数字");
            $("accfunds").focus();
            return false;
        }else if(business.trim() == ""){
            alert("请输入商业型贷款金额");
            $("business").focus();
            return false;
        }else if(!business.isNumber()){
            alert("商业型贷款必须为数字");
            $("business").focus();
            return false;
        } 
        result.loansSpan = (parseInt(business) + parseInt(accfunds)); // 贷款总额
        if(paytype == 1){ // 等额本息还款，月均还款
            var monthMoney = fGetMonthMoney1(businessRate,business,month) + fGetMonthMoney1(accfundsRate,accfunds,month);//调用函数计算
                                
            result.monthPay1 = Math.round(monthMoney * 100)/100 ; // 月均还款额 
            result.totalPay  = Math.round(monthMoney * month * 100)/100; // 还款总额            
            result.interest  = Math.round((monthMoney * month - business - accfunds) *100)/100; // 支付利息款              
        }else{ // 等额本金还款，月还款
            var totalPay = 0;
            var monthMoney = "";
            var tmp;
            for(j=0;j<month;j++) {
                //调用函数计算: 本金月还款额
                tmp = fGetMonthMoney2(businessRate,business,month,j) + fGetMonthMoney2(accfundsRate,accfunds,month,j);
                totalPay += tmp;
                tmp = Math.round(tmp*100)/100;
                monthMoney += (j+1) +"月," + tmp + "(元)<br/>";
            }                       
            result.monthPay2 = monthMoney ; // 月还款额   
            result.totalPay  = Math.round(totalPay * 100)/100; // 还款总额    
            result.interest  = Math.round((totalPay - business - accfunds) *100)/100; // 支付利息款 
        }        
	}else{ // 商业贷款或者公积金贷款		
        var rate = fGetRateData(ratetype,type, years); // 得到利率
        var loans =  $("loans").value; // 贷款总额 
        if(loans.trim() == ""){
            alert("请输入贷款金额");
            $("loans").focus();
            return false;
        }else if(!loans.isNumber()){
            alert("贷款必须为数字");
            $("loans").focus();
            return false;
        }
        result.loansSpan = loans; // 贷款总额

        if(paytype == 1){ // 等额本息还款，月均还款
            var monthMoney = fGetMonthMoney1(rate,loans,month); // 调用函数计算            
            result.monthPay1 = Math.round(monthMoney*100)/100 ; // 月均还款额 
            result.totalPay  = Math.round(monthMoney * month*100)/100; // 还款总额            
            result.interest  = Math.round((monthMoney * month - loans) *100)/100; // 支付利息款              
        }else{ // 等额本金还款，月还款
            var totalPay = 0;
            var monthMoney = "";
            var tmp;
            for(j = 0; j < month; j++) {                
                tmp = fGetMonthMoney2(rate,loans,month,j);//调用函数计算: 本金月还款额
                totalPay += tmp;
                tmp = Math.round(tmp*100) / 100;
                monthMoney += (j+1) +"月," + tmp + "(元)<br>";
            }
            result.monthPay2 = monthMoney; // 月还款额    
            result.totalPay  = Math.round(totalPay*100)/100; // 还款总额    
            result.interest  = Math.round((totalPay - loans) *100)/100; // 支付利息款  
        }
	}
    fShowResult(result);
    $("resultDiv").style.display = "";
}

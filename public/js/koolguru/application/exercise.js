$(document).ready(function () {
    var excercise = {};
    excercise = {
        renderid: '',
        questionlist: new Array(),
        currentquestion: '',
        correctquestions: new Array(),
        incorrectquestions: new Array(),
        answers: new Array(),
        renderquestioncount : 0,
        openpopup : function (){ 
            $('#koolguru-pop').click();
        },
        
        setpopuptitle : function(title){   
            $('#poptitle').text('');
            $('#poptitle').text(title);
        },
//        setpopupmsg : function(msg){
//            $('#pop-msg').text('');
//            $('#pop-msg').text(msg);
//        },
        
        setpopupmsg : function(msg){
            $('#pop-msg').text('');
            $('#pop-msg').text(msg);
        },
        
        popupclose : function(){           
            $('#pop-close').click();
        },
        
        createpopupfooterbtn : function (data){             
            var _this = this;
            var btnstr = '';            
            for(var i in data){                
                var temp = data[i];
                btnstr += "&nbsp;<input id='"+temp[1]+"' type='button' class='green-btn pop-btn margin-Top10-Btm40' value='"+temp[0]+"'>";
            }
            $('#pop-footer').html(btnstr); 
            for(var i in data){;
                var temp = new Array();
                temp = data[i];                
                if(temp[2]!=''){  
                    $('#'+temp[1]).click(function(){  
                        if($(this).attr('id')=='nextque'){
                            _this.openNextQuestion();
                        }else if($(this).attr('id')=='solution'){
                            _this.showQueSolution();
                        }else{
                            _this.popupclose();
                        }
                    });
                }else{                       
                    $('#'+temp[1]).click(function(){                        
                       _this.popupclose();
                    });
                }
            }
        },
        
        showQueSolution : function(){            
            var quelist = this.questionlist;
            var solution = '';            
            for(var i in quelist){                
                if( i == this.currentquestion ){
                    solution = quelist[i].solution;
                }
            }
            this.setpopuptitle("Question's Solution");
            this.setpopupmsg(solution);           
            return false;
            $('#pop-footer').html('');
            var footerbtn = new Array();
            var btn1 = ['Reattempt','reattempt', ''];
            var btn2 = ['Next', 'nextque', 'openNextQuestion'];
            footerbtn.push(btn1);
            footerbtn.push(btn2);
            this.createpopupfooterbtn(footerbtn);
        },
        rendercurrentquestion: function () {
            this.renderquestioncount = parseInt(parseInt(this.renderquestioncount) + 1);
            var quesobj = this.questionlist[this.currentquestion];
            var options = quesobj.options;            
            $('#' + this.renderid).html('');
            var renderhtml = '';
            renderhtml += "<br><div class='questionborder' style='margin-top:10px'>";
            renderhtml += "<h4 class='questiontitle'>" + this.renderquestioncount + '.' +quesobj.title + "</h4>";
            renderhtml += "<div class='book_date btm'>";
            renderhtml += "<ul>";
            for (key in options) {
                var obj = options[key];
                if (obj.iscorrect == '0') {
                    var optionClass = 'incorrect';
                } else {
                    var optionClass = 'correct';
                }
                renderhtml += "<li class='form-row'>";
                renderhtml += "<label class='custom-label'>";
                renderhtml += "<input type='radio' name='option' id='" + key + "' value='" + key + '-' + obj.iscorrect + "' style='position: absolute; left: 173px;'>";
                renderhtml += "<span style='position: absolute; left: 200px;'>" + obj.description + "<span></label><br/>";
                renderhtml += "<span id='" + key + "-message' style='margin-left: 24%;margin-top:-1%' class='optionmsg " + optionClass + "'></span></li>";
            }
            renderhtml += "</ul>";
            renderhtml += "</div>";
            //renderhtml += "<input name='submit' id='submit' type='button' class='green-btn big-btn margin-Top10-Btm40' value='Submit'>";
            renderhtml += "&nbsp<input name='excercisenext' id='excercisenext' type='button' class='green-btn big-btn margin-Top10-Btm40' value='Next'>";
            renderhtml += "<br><span id='error' style='color:RED; display:none'></span>";
            $('#' + this.renderid).html(renderhtml);
            if(this.renderquestioncount == this.questionlist.length){
                this.renderquestioncount = 0;
            }
            this.bindNextClick();
            this.bindSubmitClick();
        },
        bindSubmitClick: function () {
            var _this = this;
            $('#submit').click(function () {
                _this.submitquestion();
            });
        },
        submitquestion: function () {
            $('.optionmsg').each(function () {
                $(this).html('');
            });
            if ($('input[name="option"]:checked').length == 0) {
                $('#error').show();
                $('#error').html('Please choose any of them');
            } else {
                $('#error').hide();
                var val = $('input[name="option"]:checked').val();
                var split = val.split('-');
                if (parseInt(split['1']) == 1) {
                    $('#' + parseInt(split['0']) + '-' + 'message').html('Correct');
                } else {
                    $('#' + parseInt(split['0']) + '-' + 'message').html('Incorrect');
                }
            }
            //

        },
        bindNextClick: function () {
            var _this = this;
            $('#excercisenext').click(function () {
                _this.nextquestion();
            });
        },
        checkanswer : function(){
            if ($('input[name="option"]:checked').length == 0) {
                $('#error').show();
                $('#error').html('Please choose any of them');
            }else{
                $('#error').hide();
                var val = $('input[name="option"]:checked').val();
                var split = val.split('-');
                if (parseInt(split['1']) == 1) {
                    //$('#' + parseInt(split['0']) + '-' + 'message').html('Correct');
                    this.setpopuptitle('Confirmation');
                    this.setpopupmsg('Your answer is correct');
                    var footerbtn = new Array();
                    var btn1 = ['Next Question', 'nextque', 'openNextQuestion'];
                    var btn2 = ['Solution','solution', 'showQueSolution'];
                    footerbtn.push(btn1);
                    footerbtn.push(btn2);
                    this.createpopupfooterbtn(footerbtn);
                    this.openpopup();
                } else {
                    //$('#' + parseInt(split['0']) + '-' + 'message').html('Incorrect');
                    this.setpopuptitle('Confirmation');
                    this.setpopupmsg('Your answer is not correct');
                    
                    var footerbtn = new Array();
                    var btn1 = ['Reattempt','reattempt', ''];
                    var btn2 = ['Solution','solution', 'showQueSolution'];
                    var btn3 = ['Next Question', 'nextque', 'openNextQuestion'];
                    footerbtn.push(btn1);
                    footerbtn.push(btn3);
                    footerbtn.push(btn2);
                    this.createpopupfooterbtn(footerbtn);
                    this.openpopup();
                }
            }
        },
        
        showSolution : function(){
            
        },
        
        openNextQuestion : function(){ 
            this.popupclose();
            var curquestion = this.currentquestion;
            this.currentquestion = parseInt(curquestion + 1);
            if ((this.questionlist.length - 1) == curquestion) {
                this.currentquestion = 0;
            }
            this.rendercurrentquestion();
        },
        nextquestion: function () {            
            this.checkanswer();
        },
        setquestionlist: function (json) {
            var arr = [];
            var t = JSON.parse(json);
            for (var x in t) {
                arr.push(t[x]);
            }
            this.questionlist = arr;
        },
    }
    app.excercise = excercise;
});


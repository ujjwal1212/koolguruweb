$(document).ready(function () {
    var quiz = {};
    quiz = {
        renderid: '',
        questionlist: new Array(),
        currentquestion: '',
        correctquestions: new Array(),
        incorrectquestions: new Array(),
        answers: new Array(),
        renderquestioncount : 0,
        rendercurrentquestion: function () {       
            //console.log(this);
            this.renderquestioncount = parseInt(parseInt(this.renderquestioncount) + 1);
            var quesobj = this.questionlist[this.currentquestion];            
            var options = quesobj.options;            
            $('#' + this.renderid).html('');
            var renderhtml = '';
            renderhtml += "<br><div class='questionborder' style='margin-top:10px'>";
            renderhtml += "<h4 class='questiontitle'>" + quesobj.title + "</h4>";
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
                renderhtml += "<input type='radio' class='ques-option' name='option' id='" + key + "' value='" + key + '-' + obj.iscorrect + "' style='position: absolute; left: 173px;'>";
                renderhtml += "<span style='position: absolute; left: 200px;'>" + obj.description + "<span></label><br/>";
                renderhtml += "<span id='" + key + "-message' style='margin-left: 7%' class='optionmsg " + optionClass + "'></span></li>";
            }
            renderhtml += "</ul>";
            renderhtml += "</div>";
            if(this.renderquestioncount == this.questionlist.length){
                renderhtml += "<input name='submit' id='submit' type='button' class='green-btn big-btn margin-Top10-Btm40' value='Submit'>";
            }            
            if(this.renderquestioncount < this.questionlist.length){
                renderhtml += "&nbsp<input name='excercisenext' id='excercisenext' type='button' class='green-btn big-btn margin-Top10-Btm40' value='Next'>";
            }
            renderhtml += "<br><span id='error' style='color:RED; display:none'></span>";
            $('#' + this.renderid).html(renderhtml);
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
//            $('.optionmsg').each(function () {
//                $(this).html('');
//            });
//            if ($('input[name="option"]:checked').length == 0) {
//                $('#error').show();
//                $('#error').html('Please choose any of them');
//            } else {
//                $('#error').hide();
//                var val = $('input[name="option"]:checked').val();
//                var split = val.split('-');
//                if (parseInt(split['1']) == 1) {
//                    $('#' + parseInt(split['0']) + '-' + 'message').html('Correct');
//                } else {
//                    $('#' + parseInt(split['0']) + '-' + 'message').html('Incorrect');
//                }
//            }
            //
            var question_count = this.questionlist.length;
            var correct_question_count = this.correctquestions.length;
            var incorrect_question_count = this.incorrectquestions.length;
            console.log('question count '+question_count);            
                console.log(correct_question_count + incorrect_question_count);
            if((correct_question_count + incorrect_question_count) <= question_count){
                if(this.checkanswer()){
                    console.log(this);
                    var renderhtml = "<br><table border='1'>";
                    renderhtml += "<tr>";
                    renderhtml += "<td>";
                    renderhtml += "Correct Question Count</td>";
                    renderhtml += "</td>";
                    renderhtml += "<td>";
                    renderhtml += this.correctquestions.length;
                    renderhtml += "</td>";                    
                    renderhtml += "</tr>";
                    
                    renderhtml += "<tr>";
                    renderhtml += "<td>";
                    renderhtml += "Incorrect Question Count</td>";
                    renderhtml += "</td>";
                    renderhtml += "<td>";
                    renderhtml += this.incorrectquestions.length;
                    renderhtml += "</td>";
                    renderhtml += "</tr>";
                    renderhtml += "</table>";
                    $('#' + this.renderid).html(renderhtml);
                }
            }
            
                
            
        },
        bindNextClick: function () {
            var _this = this;
            $('#excercisenext').click(function () {
                _this.nextquestion();
            });
        },
        nextquestion: function () {
            var curquestion = this.currentquestion;            
            if(this.checkanswer()){
                this.currentquestion = parseInt(curquestion + 1);
                if ((this.questionlist.length - 1) == curquestion) {
                    this.currentquestion = 0;
                }
                this.rendercurrentquestion();
            }  
            
        },        
        checkanswer : function (){            
            var curquestion = this.currentquestion;
            var selectanswer = '';
            if(typeof $('input[name=option]:checked', '.custom-label').val() != 'undefined'){
                selectanswer = $('input[name=option]:checked', '.custom-label').val();
            }            
            if(selectanswer != ''){
                $('#error').html('');
                $('#error').hide();                
                var split = selectanswer.split('-');                
                if(split[1] == 0){
                    this.correctquestions.push(curquestion);
                }else if(split[1] == 1){
                    this.incorrectquestions.push(curquestion);
                }
                this.answers.push(curquestion+'-'+split[0]);
                return true;
            }else{                
                $('#error').html('Please select atleast one option');
                $('#error').show();
                return false;
            }  
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
    app.quiz = quiz;
});


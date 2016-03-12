$(document).ready(function () {
    var excercise = {};
    excercise = {
        renderid: '',
        questionlist: new Array(),
        currentquestion: '',
        correctquestions: new Array(),
        incorrectquestions: new Array(),
        answers: new Array(),
        rendercurrentquestion: function () {

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
                renderhtml += "<input type='radio' name='option' id='" + key + "' value='" + key + '-' + obj.iscorrect + "' style='position: absolute; left: 173px;'>";
                renderhtml += "<span style='position: absolute; left: 200px;'>" + obj.description + "<span></label><br/>";
                renderhtml += "<span id='" + key + "-message' style='margin-left: 24%;margin-top:-1%' class='optionmsg " + optionClass + "'></span></li>";
            }
            renderhtml += "</ul>";
            renderhtml += "</div>";
            renderhtml += "<input name='submit' id='submit' type='button' class='green-btn big-btn margin-Top10-Btm40' value='Submit'>";
            renderhtml += "&nbsp<input name='excercisenext' id='excercisenext' type='button' class='green-btn big-btn margin-Top10-Btm40' value='Next'>";
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
        nextquestion: function () {
            var curquestion = this.currentquestion;
            this.currentquestion = parseInt(curquestion + 1);
            if ((this.questionlist.length - 1) == curquestion) {
                this.currentquestion = 0;
            }
            this.rendercurrentquestion();
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


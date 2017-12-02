<template>
    <form id="theForm" class="simform" autocomplete="off">
        <div class="simform-inner" :class="{ hide : isFilled }">
            <slot :current="current" shit="shit"></slot>
            <button class="submit" type="submit">Send answers</button>
            <div class="controls">
                <button class="next" :class="{ show: showNext }" aria-label="Next" @click.prevent="nextQuestion()" ></button>
                <div class="progress"
                     role="progressbar"
                     aria-readonly="true"
                     aria-valuemin="0"
                     aria-valuemax="100"
                     :aria-valuenow="currentProgress"
                     :style="{ width : currentProgress + '%' }"></div>
                <span class="number">
                    <span class="number-current">{{ current + 1 }}</span>
                    <span class="number-total">{{ questionsCount }}</span>
                </span>
                <span class="error-message"></span>
            </div>
        </div>
        <span class="final-message" :class="{ show : isFilled }">Missatge final</span>
    </form>
</template>

<style src="./full-screen-forms.css"></style>

<script>

  export default {
    data() {
      return {
        current: 0,
        questions: [],
        questionsCount: 0,
        isFilled: false,
        showNext: false
      }
    },
    computed: {
      currentProgress: function () {
        return this.current * ( 100 / this.questionsCount )
      }
    },
    methods: {
      nextQuestion() {
        //TODO Validate using acacha forms
        // Show errors

        // check if form is filled
        if (this.current === this.questionsCount - 1) {
          this.isFilled = true;
        }

        // clear any previous error messages
        // TODO this._clearError();

        // current question
        let currentQuestion = this.questions[this.current];

        ++this.current;

        if (!this.isFilled) {
          // change the current question number/status
          this._updateQuestionNumber();

          // add class "show-next" to form element (start animations)
//          classie.addClass( this.el, 'show-next' );

          // remove class "current" from current question and add it to the next one
          // current question
          //TODO HOW TO ADD CLASS WITOUTH USING CLASSIE!
//          var nextQuestion = this.questions[ this.current ];
//          classie.removeClass( currentQuestion, 'current' );
//          classie.addClass( nextQuestion, 'current' );

        }
      },
      },
      mounted() {
        this.questions = [].slice.call(this.$el.querySelectorAll('ol > li'));
        this.questionsCount = this.questions.length;
        this.showNext = true
        // show first question
//      classie.addClass( this.questions[0], 'current' );

//      new stepsForm( theForm, {
//        onSubmit : function( form ) {
//          // hide form
//          classie.addClass( theForm.querySelector( '.simform-inner' ), 'hide' );
//
//            /*
//             form.submit()
//             or
//             AJAX request (maybe show loading indicator while we don't have an answer..)
//             */
//
//          // let's just simulate something...
//          var messageEl = theForm.querySelector( '.final-message' );
//          messageEl.innerHTML = 'Thank you! We\'ll be in touch.';
//          classie.addClass( messageEl, 'show' );
//        }
//      } );
      }
  }

</script>
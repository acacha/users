<template>
    <simple-step-form>
        <template slot-scope="props">
            <ol class="questions" slot-scope="props">
                <li :class="{ current : props.current == 0 }" >
                    <span><label for="q1">{{ props.current }} | What's your email?</label></span>
                    <input id="email" name="email" type="email"/>
                </li>
                <li :class="{ current : props.current == 1 }">
                    <span><label for="q2">Your name?</label></span>
                    <input id="name" name="name" type="text"/>
                </li>
                <li :class="{ current : props.current == 2 }">
                    <span><label for="q3">What time do you go to work?</label></span>
                    <input id="q3" name="q3" type="text"/>
                </li>
                <li :class="{ current : props.current == 3 }">
                    <span><label for="q4">How do you like your veggies?</label></span>
                    <input id="q4" name="q4" type="text"/>
                </li>
            </ol>
        </template>
    </simple-step-form>
</template>

<script>

  import SimpleStepForm from './forms/SimpleStepForm.vue'

  export default {
    components: {
      'simple-step-form' : SimpleStepForm
    }
  }

</script>
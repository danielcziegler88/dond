<template>
  <div></div>
</template>

<script>
export default {
  name: "SideMenu",
  components: {},
  data: function() {
    return {
      collapse: true
    };
  },
  props: {
    game: {
      type: Object
    }
  },
  watch: {},
  methods: {
    newGame() {
      let emit = true;
      if (this.game.inplay) {
        let conf = confirm("Are you sure you want to start a new game?");
        if (!conf) {
          emit = false;
        }
      }
      if (emit) {
        this.$emit("newgame");
      }
    }
  },
  computed: {},
  mounted() {}
};
</script>

<style></style>

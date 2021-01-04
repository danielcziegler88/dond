<template>
  <div>
    <div :style="boxStyle">
      <el-button
        :type="box.color"
        style="width: 100%; margin-bottom: 4px; font-size: 20pt"
        @click="$emit('playBox')"
        ><b>{{ prizeString }}</b></el-button
      >
    </div>
  </div>
</template>

<script>
export default {
  name: "Box",
  components: {},
  data: function() {
    return {
      collapse: true
    };
  },
  props: {
    box: {
      type: Object
    },
    prizesInPlay: {
      type: Number,
      default: 22
    },
    ofColorInPlay: {
      type: Number,
      default: 12
    }
  },
  watch: {},
  methods: {},
  computed: {
    prizeString: function() {
      let temp;
      if (this.box.value < 1) {
        temp = this.box.value * 100;
        return temp + "p";
      } else {
        temp = this.box.value;
        return "£" + new Intl.NumberFormat().format(temp);
      }
    },
    boxStyle: function() {
      if (this.box.inplay) {
        if (this.prizesInPlay <= 6) {
          return { display: "block" };
        } else {
          return { visibility: "visible" };
        }
      } else {
        if (this.ofColorInPlay > 0) {
          if (this.prizesInPlay <= 6) {
            return { display: "none" };
          } else {
            return { visibility: "hidden" };
          }
        } else {
          return { visibility: "hidden" };
        }
      }
    }
  },
  mounted() {}
};
</script>

<style></style>

<template>
  <div id="app">
    <el-container class="dond-appcontainer">
      <el-container>
        <el-main>
          <div v-if="fatal">FATAL ERROR</div>
          <div v-else>
            <div v-if="game.inplay">
              <el-row align="bottom">
                <el-col class="text-center" :span="boxColumnSize">
                  <div v-for="(box, index) in game.prizes" :key="index">
                    <Box
                      v-if="box.color == 'primary'"
                      :box="box"
                      :prizesInPlay="prizesInPlay"
                      v-on:playBox="playBox(index)"
                      :ofColorInPlay="bluesInPlay"
                    ></Box>
                  </div>
                </el-col>

                <el-col
                  :span="infoColumnSize"
                  style="padding-left: 10px; padding-right: 10px"
                >
                  <el-row style="margin-bottom: 20px" class="text-center">
                    <el-col :span="8" style="padding-right: 10px">
                      <el-card shadow="hover">
                        <div slot="header"><b>Boxes Remaining</b></div>
                        {{ prizesInPlay }}
                      </el-card>
                    </el-col>
                    <el-col :span="8">
                      <el-card shadow="hover">
                        <div slot="header"><b>Boxes Opened</b></div>
                        {{ boxesOpened }}
                      </el-card>
                    </el-col>
                    <el-col :span="8" style="padding-left: 10px">
                      <el-card shadow="hover">
                        <div slot="header"><b>Prize Average</b></div>
                        {{ prizeAverage }}
                      </el-card>
                    </el-col>
                  </el-row>
                  <el-alert
                    v-if="offerDue"
                    :title="'You are now at: ' + prizesInPlay + ' box'"
                    description="At this point you would typically want to trigger an offer from the banker.."
                    type="warning"
                    :closable="false"
                    center
                    show-icon
                  ></el-alert>
                  <el-alert
                    v-else
                    title="Please continue to open boxes.."
                    description="Or trigger an offer from the banker."
                    type="info"
                    :closable="false"
                    center
                  ></el-alert>
                  <el-row style="margin-top: 20px">
                    <el-col :span="12" style="padding-right: 5px">
                      <el-button
                        @click="newGame"
                        style="width:100%"
                        type="primary"
                        plain
                        ><i class="el-icon-circle-plus-outline"></i> New
                        Game</el-button
                      >
                    </el-col>
                    <el-col :span="12" style="padding-left: 5px">
                      <el-button
                        @click="triggerOffer"
                        :loading="loading"
                        style="width:100%"
                        type="danger"
                        plain
                        ><i class="el-icon-phone-outline"></i>
                        <span v-if="offerDue"><b>Trigger Banker Offer</b></span
                        ><span v-else>Trigger Banker Offer</span></el-button
                      >
                    </el-col>
                  </el-row>
                </el-col>

                <el-col class="text-center" :span="boxColumnSize"
                  ><div v-for="(box, index) in game.prizes" :key="index">
                    <Box
                      v-if="box.color == 'danger'"
                      :box="box"
                      :prizesInPlay="prizesInPlay"
                      v-on:playBox="playBox(index)"
                      :ofColorInPlay="redsInPlay"
                    ></Box></div
                ></el-col>
              </el-row>
            </div>
            <div v-else>
              <el-button
                @click="newGame"
                style="width:100%"
                type="primary"
                plain
                ><i class="el-icon-circle-plus-outline"></i> New Game</el-button
              >
            </div>
          </div>
        </el-main>
      </el-container>
    </el-container>
  </div>
</template>

<script>
import Box from "./components/Box.vue";
export default {
  name: "app",
  components: { Box },
  data: function() {
    return {
      game: {
        inplay: false
      },
      menuCollapse: true,
      menuBgCol: "rgb(238, 241, 246)",
      boxColumnSize: 5,
      loading: false,
      fatal: false
    };
  },
  props: {},
  watch: {},
  methods: {
    hideMenu() {
      this.menuCollapse = true;
    },
    newGame() {
      let emit = true;
      if (this.game.inplay) {
        let conf = confirm("Are you sure you want to start a new game?");
        if (!conf) {
          emit = false;
        }
      }
      if (emit) {
        this.game = {
          inplay: true,
          steps: [],
          prizes: [
            { position: 1, value: 0.01, color: "primary", inplay: true },
            { position: 2, value: 0.1, color: "primary", inplay: true },
            { position: 3, value: 0.5, color: "primary", inplay: true },
            { position: 4, value: 1, color: "primary", inplay: true },
            { position: 5, value: 5, color: "primary", inplay: true },
            { position: 6, value: 10, color: "primary", inplay: true },
            { position: 7, value: 50, color: "primary", inplay: true },
            { position: 8, value: 100, color: "primary", inplay: true },
            { position: 9, value: 250, color: "primary", inplay: true },
            { position: 10, value: 500, color: "primary", inplay: true },
            { position: 11, value: 750, color: "primary", inplay: true },
            { position: 12, value: 1000, color: "danger", inplay: true },
            { position: 13, value: 3000, color: "danger", inplay: true },
            { position: 14, value: 5000, color: "danger", inplay: true },
            { position: 15, value: 10000, color: "danger", inplay: true },
            { position: 16, value: 15000, color: "danger", inplay: true },
            { position: 17, value: 20000, color: "danger", inplay: true },
            { position: 18, value: 35000, color: "danger", inplay: true },
            { position: 19, value: 50000, color: "danger", inplay: true },
            { position: 20, value: 75000, color: "danger", inplay: true },
            { position: 21, value: 100000, color: "danger", inplay: true },
            { position: 22, value: 250000, color: "danger", inplay: true }
          ],
          offers: []
        };
      }
      this.hideMenu();
    },
    playBox(index) {
      this.game.prizes[index].inplay = false;
      let temp = {
        type: "box opened",
        box: this.game.prizes[index]
      };
      this.game.steps.push(temp);
    },
    storeAndGenerateOffer(offervalue) {
      this.loading = true;
      let percofavg = (offervalue / this.prizeAverageNumber) * 100;
      const postdata = new FormData();
      postdata.append("board", this.prizesByteString);
      postdata.append("boxesremaining", this.prizesInPlay);
      postdata.append("boxesopened", this.boxesOpened);
      postdata.append("average", this.prizeAverageNumber);
      postdata.append("offer", offervalue);
      postdata.append("percentofaverage", percofavg);
      postdata.append("gameoverview", JSON.stringify(this.game.steps));
      this.$http
        .post("/api/v1/offers", postdata)
        .then(response => {
          if (response.data.status) {
            let temp = {
              board: this.prizesByteString,
              average: this.prizeAverageNumber,
              real: {
                offervalue: parseInt(offervalue),
                percentofaverage: Math.round(percofavg)
              },
              simulated: {
                lowoffer: response.data.low_offer,
                highoffer: response.data.high_offer,
                percentrange: response.data.offer_range
              }
            };
            this.game.offers.push(temp);
            let step = {
              type: "offer",
              offer: temp
            };
            this.game.steps.push(step);
            this.$alert(
              "The Generated Offer is: £" +
                response.data.low_offer +
                " - £" +
                response.data.high_offer,
              "Generated Offer",
              {
                confirmButtonText: "OK",
                callback: action => {
                  if (action) {
                    //nothing
                  }
                }
              }
            );
            this.loading = false;
          } else {
            console.error("error storing/generating offer: " + response);
            this.loading = false;
            this.fatal = true;
          }
        })
        .catch(error => {
          console.error("error storing/generating offer: " + error);
          this.loading = false;
          this.fatal = true;
        });
    },
    triggerOffer() {
      this.$prompt(
        "Please enter the offer from the Banker",
        "Enter Banker Offer",
        {
          confirmButtonText: "OK",
          cancelButtonText: "Cancel"
        }
      )
        .then(({ value }) => {
          this.storeAndGenerateOffer(value);
        })
        .catch(() => {
          console.log("Banker offer cancelled");
        });
    }
  },
  computed: {
    menuBgWidth: function() {
      if (this.menuCollapse) {
        return "64px";
      } else {
        return "200px";
      }
    },
    menuStyle: function() {
      return {
        width: this.menuBgWidth,
        "background-color": this.menuBgCol
      };
    },
    infoColumnSize: function() {
      let temp = this.boxColumnSize * 2;
      return 24 - temp;
    },
    prizesInPlay: function() {
      let counter = 0;
      for (let i = 0; i < this.game.prizes.length; i++) {
        if (this.game.prizes[i].inplay) {
          counter++;
        }
      }
      return counter;
    },
    bluesInPlay: function() {
      let counter = 0;
      for (let i = 0; i < this.game.prizes.length; i++) {
        if (
          this.game.prizes[i].inplay &&
          this.game.prizes[i].color == "primary"
        ) {
          counter++;
        }
      }
      return counter;
    },
    redsInPlay: function() {
      let counter = 0;
      for (let i = 0; i < this.game.prizes.length; i++) {
        if (
          this.game.prizes[i].inplay &&
          this.game.prizes[i].color == "danger"
        ) {
          counter++;
        }
      }
      return counter;
    },
    prizesByteString: function() {
      let out = "";
      for (let i = 0; i < this.game.prizes.length; i++) {
        if (this.game.prizes[i].inplay) {
          out = out + "1";
        } else {
          out = out + "0";
        }
      }
      return out;
    },
    boxesOpened: function() {
      return 22 - this.prizesInPlay;
    },
    roundNumber: function() {
      if (this.boxesOpened <= 5) {
        return 1;
      } else if (this.boxesOpened <= 8) {
        return 2;
      } else if (this.boxesOpened <= 11) {
        return 3;
      } else if (this.boxesOpened <= 13) {
        return 4;
      } else if (this.boxesOpened <= 16) {
        return 5;
      } else if (this.boxesOpened <= 19) {
        return 6;
      } else {
        return 7;
      }
    },
    prizeChance: function() {
      let temp = (1 / this.prizesInPlay) * 100;
      return Math.round(temp) + "%";
    },
    prizeAverage: function() {
      let tot = 0;
      for (let i = 0; i < this.game.prizes.length; i++) {
        if (this.game.prizes[i].inplay) {
          tot = tot + this.game.prizes[i].value;
        }
      }
      return (
        "£" +
        new Intl.NumberFormat().format(Math.round(tot / this.prizesInPlay))
      );
    },
    prizeAverageNumber: function() {
      let temp = this.prizeAverage.replace("£", "");
      return temp.replace(",", "");
    },
    offerDue: function() {
      if (
        this.boxesOpened == 5 ||
        this.boxesOpened == 8 ||
        this.boxesOpened == 11 ||
        this.boxesOpened == 14 ||
        this.boxesOpened == 17 ||
        this.boxesOpened == 20
      ) {
        return true;
      } else {
        return false;
      }
    }
  },
  mounted() {}
};
</script>

<style>
html {
  height: 100%;
}
body {
  font-family: "Helvetica Neue", Helvetica, "PingFang SC", "Hiragino Sans GB",
    "Microsoft YaHei", "微软雅黑", Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
  height: 100%;
  margin: 0px;
}
.dond-appcontainer {
  height: 100vh;
}
.dond-sidemenu:not(.el-menu--collapse) {
  width: 200px;
}
.text-center {
  text-align: center;
}
.el-card {
  font-size: 18pt;
}
.el-alert {
  font-size: 14pt;
}
</style>

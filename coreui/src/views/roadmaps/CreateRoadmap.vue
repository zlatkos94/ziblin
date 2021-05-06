<template>
  <CRow>
    <CCol col="12" lg="6">
      <CCard no-header>
        <CCardBody>
          <h3>
            Create Roadmap
          </h3>
          <CAlert
              :show.sync="dismissCountDown"
              color="primary"
              fade
          >
            ({{ dismissCountDown }}) {{ message }}
          </CAlert>
          <p>
            Client name
          </p>
<!--          <CInput type="text" placeholder="Input client name" v-model="query" v-on:keyup="autoComplete"></CInput>-->
<!--          <div className="panel-footer" v-if="results.length">-->
<!--            <ul className="list-group">-->
<!--              <li class='form-control' v-for="result in results" v-bind:key="result.id" :value='result.id'-->
<!--                  @click="getName(result)">-->
<!--                {{ result.company_name }}-->
<!--              </li>-->
<!--            </ul>-->
<!--          </div>-->

          <CInput type="text" placeholder="Input client name" v-model="query" v-on:keyup="autoComplete"></CInput>
          <div class="form-group" v-if="results.length">
            <ul class="list-group">
              <li class="list-group-item list-group-item-action" v-for="result in results" v-bind:key="result.id" :value='result.id'
                  @click="getName(result)">
                {{ result.company_name }}
              </li>
            </ul>
          </div>

<!--          <div class="form-group">-->
<!--            <label>Select Country:</label>-->
<!--            <select class='form-control' v-model="query" v-on:keyup="autoComplete">-->
<!--              <option v-for='result in results'>{{ result.company_name }}</option>-->
<!--            </select>-->
<!--          </div>-->




          <!--            <CSelect-->
          <!--                label="test"-->
          <!--                :options="results.company_name"-->

          <!--                :value.sync="roadmap.fk_client"-->
          <!--            >-->
          <!--            </CSelect>-->


          <CSelect
              label="PromoMaterials"
              :value.sync="roadmap.fk_promo_material"
              :plain="true"
              :options="promoMaterials"
          >
          </CSelect>


          <CInput label="Applies to date" type="date" v-model="roadmap.applies_to_date"></CInput>
          <CInput textarea="true" label="description" :rows="9" placeholder="description.."
                  v-model="roadmap.description"></CInput>

          <CButton color="primary" @click="store()">Create</CButton>
          <CButton color="primary" @click="goBack">Back</CButton>
        </CCardBody>
      </CCard>
    </CCol>
  </CRow>
</template>
<script>
import axios from 'axios'

export default {
  data() {
    return {
      client_id: null,
      query: '',
      results: [],
      roadmap: {
        description: '',
        applies_to_date: '',
        fk_client: null,
        fk_promo_material: 1,
      },
      clients: [],
      promoMaterials: [],
      message: '',
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false
    }
  },
  methods: {
    autoComplete() {
      this.results = [];
      if (this.query.length > 0) {
        axios.get('http://127.0.0.1:8000/api/getClients?', {params: {query: this.query}}).then(response => {
          this.results = response.data;
          console.log(this.results);
        });
      }
    },
    getName: function (name) {
      this.query = name.company_name;
      this.roadmap.fk_client = name.id;
      this.results = [];
    },
    goBack() {
      this.$router.go(-1)
      // this.$router.replace({path: '/users'})
    },
    store() {
      let self = this;
      axios.post(this.$apiAdress + '/api/roadmaps?token=' + localStorage.getItem("api_token"),
          self.roadmap
      )
          .then(function (response) {
            self.roadmap = {
              description: '',
              applies_to_date: '',
              fk_client: null,
              fk_promo_material: null,
            };
            self.message = 'Successfully created client.';
            self.showAlert();
          }).catch(function (error) {
        if (error.response.data.message == 'The given data was invalid.') {
          self.message = '';
          for (let key in error.response.data.errors) {
            if (error.response.data.errors.hasOwnProperty(key)) {
              self.message += error.response.data.errors[key][0] + '  ';
            }
          }
          self.showAlert();
        } else {
          console.log(error);
          self.$router.push({path: 'login'});
        }
      });
    },
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert() {
      this.dismissCountDown = this.dismissSecs
    },
  },
  mounted: function () {
    // this.getRegions();
    let self = this;
    axios.get(this.$apiAdress + '/api/roadmaps/create?token=' + localStorage.getItem("api_token"))
        .then(function (response) {
          self.clients = response.data.clients;
          self.promoMaterials = response.data.promoMaterials;
        }).catch(function (error) {
      console.log(error);
      self.$router.push({path: 'login'});
    });
  }
}
</script>
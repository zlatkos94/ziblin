<template>
  <CRow>
    <CCol col="12" lg="6">
      <CCard no-header>
        <CCardBody>
          <h3>
            Create Region
          </h3>
          <CAlert
              :show.sync="dismissCountDown"
              color="primary"
              fade
          >
            ({{dismissCountDown}}) {{ message }}
          </CAlert>

          <CInput label="Region name" type="text" placeholder="name" v-model="region.name"></CInput>

          <CInput textarea="true" label="Description" :rows="9" placeholder="Description.." v-model="region.description"></CInput>
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
  name: 'EditUser',
  props: {
    caption: {
      type: String,
      default: 'User id'
    },
  },
  data: () => {
    return {
      region: {
        name: '',
        description: '',
      },
      statuses: [],
      message: '',
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
      // this.$router.replace({path: '/users'})
    },
    store() {
      let self = this;
      axios.post(  this.$apiAdress + '/api/regions?token=' + localStorage.getItem("api_token"),
          self.region
      )
          .then(function (response) {
            self.region = {
              name: '',
              description: '',
            };
            self.message = 'Successfully created region.';
            self.showAlert();
          }).catch(function (error) {
        if(error.response.data.message == 'The given data was invalid.'){
          self.message = '';
          for (let key in error.response.data.errors) {
            if (error.response.data.errors.hasOwnProperty(key)) {
              self.message += error.response.data.errors[key][0] + '  ';
            }
          }
          self.showAlert();
        }else{
          console.log(error);
          self.$router.push({ path: 'login' });
        }
      });
    },
    countDownChanged (dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
  },
  mounted: function(){
    let self = this;
    axios.get(  this.$apiAdress + '/api/regions/create?token=' + localStorage.getItem("api_token"))
        .then(function (response) {
          self.statuses = response.data;
        }).catch(function (error) {
      console.log(error);
      self.$router.push({ path: 'login' });
    });
  }
}

</script>

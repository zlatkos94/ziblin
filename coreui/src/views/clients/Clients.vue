<template>
  <CRow>
    <CCol col="12" xl="12">
<!--      <input type="text" v-model="keywords">-->
      <transition name="slide">
        <CCard>
          <CCardBody>
            <CButton color="primary" @click="createClient()">Create Clients</CButton>
            <CAlert
                :show.sync="dismissCountDown"
                color="primary"
                fade
            >
              ({{dismissCountDown}}) {{ message }}
            </CAlert>
            <CDataTable
                hover
                :items="items"
                :fields="fields"
                :items-per-page="10"
                pagination

                table-filter
                sorter
            >
              <template #user_name="{item}">
                <td>
                  <strong>{{item.user_name}}</strong>
                </td>
              </template>
              <template #author="{item}">
                <td>
                  <strong>{{item.company_name}}</strong>
                </td>
              </template>
              <template #title="{item}">
                <td>
                  <strong>{{item.client_name}}</strong>
                </td>
              </template>
              <template #author="{item}">
                <td>
                  <strong>{{item.phone}}</strong>
                </td>
              </template>
              <template #region_name="{item}">
                <td>
                  <strong>{{item.region_name}}</strong>
                </td>
              </template>
              <template #author="{item}">
                <td>
                  <strong>{{item.city}}</strong>
                </td>
              </template>
              <template #show="{item}">
                <td>
                  <CButton color="primary" @click="showClient( item.id )">Show</CButton>
                </td>
              </template>
              <template #edit="{item}">
                <td>
                  <CButton color="primary" @click="editClient( item.id )">Edit</CButton>
                </td>
              </template>
              <template #delete="{item}">
                <td>
                  <CButton v-if="item.id" color="danger" @click="deleteClient( item.id )">Delete</CButton>
                </td>
              </template>
            </CDataTable>
          </CCardBody>
        </CCard>
      </transition>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Clients',
  data: () => {
    return {
      keywords: null,
      items: [],
      /*
      fields: [
        {key: 'author'},
        {key: 'title'},
        {key: 'content'},
        {key: 'applies_to_date'},
        {key: 'status'},
        {key: 'note_type'},
        {key: 'show'},
        {key: 'edit'},
        {key: 'delete'}
      ],
      */
      fields: ['id','user_name','company_name','client_name','phone','region_name','city','show','edit','delete'],
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      you: null,
      message: '',
      showMessage: false,
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false
    }
  },
  computed: {
  },
  methods: {
    getRowCount (items) {
      return items.length
    },
    clientLink (id) {
      return `clients/${id.toString()}`
    },
    editLink (id) {
      return `clients/${id.toString()}/edit`
    },
    showClient ( id ) {
      const clientLink = this.clientLink( id );
      this.$router.push({path: clientLink});
    },
    editClient ( id ) {
      const editLink = this.editLink( id );
      this.$router.push({path: editLink});
    },
    deleteClient ( id ) {
      let self = this;
      let clientId = id;
      axios.post(  this.$apiAdress + '/api/clients/' + id + '?token=' + localStorage.getItem("api_token"), {
        _method: 'DELETE'
      })
          .then(function (response) {
            self.message = 'Successfully deleted Client.';
            self.showAlert();
            self.getClients();
          }).catch(function (error) {
        console.log(error);
        // self.$router.push({ path: '/login' });
      });
    },
    createClient () {
      this.$router.push({path: 'clients/create'});
    },
    countDownChanged (dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
    getClients (){
      let self = this;
      axios.get(this.$apiAdress + '/api/clients?token=' + localStorage.getItem("api_token"),{params: { keywords: this.keywords }})
          .then(function (response) {
            self.items = response.data;
          }).catch(function (error) {
        console.log(error);
        // self.$router.push({ path: '/login' });
      });
    }
  },
  mounted: function(){
    this.getClients();
  }
}

</script>
<style scoped>
.card-body >>> table > tbody > tr > td {
  cursor: pointer;
}
</style>

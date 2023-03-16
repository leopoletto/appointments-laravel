import { SET_TESTE } from './Types/mutation-types'

const state = {
  teste:'Oi, eu sou um teste'
};

const actions = {
};

const mutations = {
  [SET_TESTE](state, teste) {
    state.teste = teste;
},
};

const getters = {
  teste: state=> state.teste,
};

export default {
  state,
  actions,
  mutations,
  getters,
}
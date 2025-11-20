import transactionsService from './transactionsService';

export default {
  list() {
    return transactionsService.getAll({ type: 'income' });
  },
  create(data) {
    return transactionsService.create({ ...data, type: 'income' });
  },
  update(id, data) {
    return transactionsService.update(id, { ...data, type: 'income' });
  },
  delete(id) {
    return transactionsService.delete(id);
  }
}

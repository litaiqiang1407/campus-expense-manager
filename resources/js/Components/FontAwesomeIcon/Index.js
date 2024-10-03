import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

import { 
  faArrowLeft, 
  faEye, 
  faEyeSlash, 
  faHome, 
  faWallet, 
  faPlus, 
  faMoneyBills, 
  faUser, 
  faCircleQuestion, 
  faBell, 
  faMagnifyingGlass, 
  faEllipsisVertical, 
  faChevronDown, 
  faXmark
} from '@fortawesome/free-solid-svg-icons';

import { faCircleQuestion as faCircleQuestionRegular } from '@fortawesome/free-regular-svg-icons';

import { faGoogle } from '@fortawesome/free-brands-svg-icons';

library.add(
  faArrowLeft,
  faMagnifyingGlass,
  faEllipsisVertical,
  faChevronDown,
  faEye,
  faEyeSlash,
  faHome,
  faWallet,
  faPlus,
  faMoneyBills,
  faUser,
  faCircleQuestion,
  faBell,
  faGoogle,
  faXmark ,
  faCircleQuestionRegular
);

export default FontAwesomeIcon;

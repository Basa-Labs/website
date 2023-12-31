import {__} from '@wordpress/i18n';
import {differenceInDays, parseISO} from 'date-fns';

const CompareFooter = ({startDate, endDate}) => {

  const startDateISO = parseISO(startDate);
  const endDateISO = parseISO(endDate);

  // get amount of days between start and end date with date-fns
  const days = differenceInDays(endDateISO, startDateISO) + 1;
  const text = (days === 1) ? __('vs. previous day', 'burst-statistics') : __(
      'vs. previous %s days', 'burst-statistics');

  // replace %s with days
  const textWithDays = text.replace('%s', days);

  return (
      <>
        <p className={'burst-small-text'}>{textWithDays}</p>
      </>
  );
};

export default CompareFooter;
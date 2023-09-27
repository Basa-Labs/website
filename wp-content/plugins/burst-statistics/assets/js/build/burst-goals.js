/**
 * Setup event listeners for goals.
 */
const burst_goals_setup = () => {
  // loop through goals and remove any that don't match the current path or
  // don't have a path
  for (let i = 0; i < burst.goals.length; i++) {
    let goal = burst.goals[i];
    if (goal.url && (goal.url !== window.location.pathname && goal.url !== '*')) {
      burst.goals.splice(i, 1);
    }
  }
  // loop through all goals and setup event listeners
  for (let i = 0; i < burst.goals.length; i++) {
    let goal = burst.goals[i];
    switch (goal.type) {
      case 'views':
        burst_setup_viewport_tracker(goal);
        break;
      case 'clicks':
      default:
        burst_setup_click_tracker(goal);
        break;
    }
  }
};

/**
 * Setup a viewport tracker for a goal.
 * @param goal
 */
const burst_setup_viewport_tracker = (goal) => {
  let selector = goal.setup.attribute === 'id' ? '#' : '.';
  let elements = document.querySelectorAll( selector + goal.setup.value);

  for (let i = 0; i < elements.length; i++) {
    let element = elements[i];
    // if the element is already in the viewport, trigger the goal
    if (burst_is_element_in_viewport(element)) {
      burst_goal_triggered(goal);
    } else {
      // otherwise, setup a scroll listener
      window.addEventListener('scroll', () => burst_listener_view(element, goal), true );
    }
  }
};

/**
 * Check if an element is in the viewport.
 * @param element
 * @returns {boolean}
 */
const burst_is_element_in_viewport = (element) => {
  let rect = element.getBoundingClientRect();
  return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <=
      (window.innerHeight || document.documentElement.clientHeight) && /* or $(window).height() */
      rect.right <= (window.innerWidth || document.documentElement.clientWidth) /* or $(window).width() */
  );
};

/**
 * Function to check and trigger a goal when an element is in the viewport.
 * @param element
 * @param goal
 */
const burst_listener_view = (element, goal) => {
    if (burst_is_element_in_viewport(element)) {
      burst_goal_triggered(goal);
      // @todo event listener does not get removed
      window.removeEventListener('scroll',
          () => burst_listener_view(element, goal), true);
    }
}

/**
 * Setup a click tracker for a goal.
 * @param goal
 */
const burst_setup_click_tracker = (goal) => {
  let selector = goal.setup.attribute === 'id' ? '#' : '.';
  let elements = document.querySelectorAll(selector + goal.setup.value);
  for (let i = 0; i < elements.length; i++) {
    let element = elements[i];
    element.addEventListener('click',
        () => burst_listener_click(element, goal), {once: true} );
  }
};

/**
 * Function to check and trigger a goal when an element is clicked.
 * @param element
 * @param goal
 */
const burst_listener_click = (element, goal) => {
  burst_goal_triggered(goal);
  element.removeEventListener('scroll',
      () => burst_listener_view(element, goal), true);
}

/**
 * Trigger a goal and add to the completed goals array.
 * @param goal
 */
const burst_goal_triggered = (goal) => {
  // if burst_completed_goals does not contain goal.id, add it
  if (burst_completed_goals.indexOf(goal.ID) === -1) {
    burst_completed_goals.push(goal.ID);
  }
};

/**
 * Default export for goals.
 */
export default () => {
  burst_goals_setup();
};
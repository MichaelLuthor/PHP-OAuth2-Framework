<?php
namespace PHPMD {
/**
 * This is the abstract base class for pmd rules.
 * @author    Manuel Pichler <mapi@phpmd.org>
 * @copyright 2008-2014 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 *
 * @SuppressWarnings(PHPMD)
 */
abstract class AbstractRule {
    /**
     * Returns the name for this rule instance.
     *
     * @return string
     */
    public function getName(){}

    /**
     * Sets the name for this rule instance.
     *
     * @param string $name The rule name.
     *
     * @return void
     */
    public function setName($name){}

    /**
     * Returns the version since when this rule is available or <b>null</b>.
     *
     * @return string
     */
    public function getSince(){}

    /**
     * Sets the version since when this rule is available.
     *
     * @param string $since The version number.
     *
     * @return void
     */
    public function setSince($since){}

    /**
     * Returns the violation message text for this rule.
     *
     * @return string
     */
    public function getMessage(){}

    /**
     * Sets the violation message text for this rule.
     *
     * @param string $message The violation message
     *
     * @return void
     */
    public function setMessage($message){}

    /**
     * Returns an url will external information for this rule.
     *
     * @return string
     */
    public function getExternalInfoUrl(){}

    /**
     * Sets an url will external information for this rule.
     *
     * @param string $externalInfoUrl The info url.
     *
     * @return void
     */
    public function setExternalInfoUrl($externalInfoUrl){}

    /**
     * Returns the description text for this rule instance.
     *
     * @return string
     */
    public function getDescription(){}

    /**
     * Sets the description text for this rule instance.
     *
     * @param string $description The description text.
     *
     * @return void
     */
    public function setDescription($description){}

    /**
     * Returns a list of examples for this rule.
     *
     * @return array(string)
     */
    public function getExamples(){}

    /**
     * Adds a code example for this rule.
     *
     * @param string $example The code example.
     *
     * @return void
     */
    public function addExample($example){}

    /**
     * Returns the priority of this rule.
     *
     * @return integer
     */
    public function getPriority(){}

    /**
     * Set the priority of this rule.
     *
     * @param integer $priority The rule priority
     *
     * @return void
     */
    public function setPriority($priority){}

    /**
     * Returns the name of the parent rule-set instance.
     *
     * @return string
     */
    public function getRuleSetName(){}

    /**
     * Sets the name of the parent rule set instance.
     *
     * @param string $ruleSetName The rule-set name.
     *
     * @return void
     */
    public function setRuleSetName($ruleSetName){}

    /**
     * Returns the violation report for this rule.
     *
     * @return \PHPMD\Report
     */
    public function getReport(){}

    /**
     * Sets the violation report for this rule.
     *
     * @param \PHPMD\Report $report
     * @return void
     */
    public function setReport(Report $report){}

    /**
     * Adds a configuration property to this rule instance.
     *
     * @param string $name
     * @param string $value
     * @return void
     */
    public function addProperty($name, $value){}

    /**
     * Returns the value of a configured property as a boolean or throws an
     * exception when no property with <b>$name</b> exists.
     *
     * @param string $name
     * @return boolean
     * @throws \OutOfBoundsException When no property for <b>$name</b> exists.
     */
    public function getBooleanProperty($name){}

    /**
     * Returns the value of a configured property as an integer or throws an
     * exception when no property with <b>$name</b> exists.
     *
     * @param string $name
     * @return integer
     * @throws \OutOfBoundsException When no property for <b>$name</b> exists.
     */
    public function getIntProperty($name){}


    /**
     * Returns the raw string value of a configured property or throws an
     * exception when no property with <b>$name</b> exists.
     *
     * @param string $name
     * @return string
     * @throws \OutOfBoundsException When no property for <b>$name</b> exists.
     */
    public function getStringProperty($name){}

    /**
     * This method adds a violation to all reports for this violation type and
     * for the given <b>$node</b> instance.
     *
     * @param \PHPMD\AbstractNode $node
     * @param array $args
     * @param mixed $metric
     * @return void
     */
    protected function addViolation(AbstractNode $node,array $args = array(),$metric = null) {}

    /**
     * This method should implement the violation analysis algorithm of concrete
     * rule implementations. All extending classes must implement this method.
     * @param \PHPMD\AbstractNode $node
     * @return void
     */
    abstract public function apply(AbstractNode $node);
}

/**
 * This is an abstract base class for PHPMD code nodes, it is just a wrapper
 * around PDepend's object model.
 * @author    Manuel Pichler <mapi@phpmd.org>
 * @copyright 2008-2014 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 */
abstract class AbstractNode {}

/**
 * The report class collects all found violations and further information about
 * a PHPMD run.
 *
 * @author    Manuel Pichler <mapi@phpmd.org>
 * @copyright 2008-2014 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class Report {}
}

namespace PHPMD\Rule {
/**
 * This interface is used to mark a rule implementation as function aware.
 * @author    Manuel Pichler <mapi@phpmd.org>
 * @copyright 2008-2014 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 */
interface FunctionAware{}
}

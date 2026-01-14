# Restaurant Booking System - Performance Test Plan

## 1. Overview

### 1.1 Purpose
This document outlines the performance testing strategy for the Restaurant Booking System to ensure it meets performance requirements under various load conditions.

### 1.2 Scope
Performance testing will cover:
- Load testing
- Stress testing
- Endurance testing
- Spike testing
- Session management testing
- Concurrent user scenarios

### 1.3 System Under Test (SUT)
- **Application Type**: PHP-based Web Application
- **Web Server**: Apache (XAMPP)
- **Session Management**: PHP Sessions (file-based)
- **Technology Stack**: PHP, HTML, CSS, JavaScript (AJAX)
- **Architecture**: Session-based, no database

## 2. Test Objectives

### 2.1 Performance Goals
- **Response Time**: Page load < 2 seconds (95th percentile)
- **API Response**: AJAX endpoints < 500ms (95th percentile)
- **Concurrent Users**: Support 50+ concurrent users
- **Throughput**: Handle 100+ requests per second
- **Error Rate**: < 1% under normal load
- **Session Handling**: Proper session management under load
- **Resource Usage**: CPU < 80%, Memory < 80% under normal load

### 2.2 Key Scenarios to Test
1. **Home Page Load**: Initial page load performance
2. **Customer Information Submission**: Form processing performance
3. **Menu Browsing**: Menu display performance
4. **Cart Operations**: Add/remove items performance
5. **Payment Processing**: Payment form submission
6. **Session Expiration**: Handling expired sessions
7. **Concurrent Bookings**: Multiple users booking simultaneously

## 3. Test Environment

### 3.1 Test Environment Setup
- **Server**: Local XAMPP or remote server
- **Base URL**: http://localhost/restaurant-booking/ (configurable)
- **Test Tool**: Apache JMeter
- **Monitoring**: Server resource monitoring (CPU, Memory, Disk I/O)

### 3.2 Prerequisites
- Apache JMeter installed (https://jmeter.apache.org/download_jmeter.cgi)
- Java Runtime Environment (JRE) 8 or higher
- PHP/Apache server running
- Network access to test server
- Server monitoring tools (optional)

## 4. Test Scenarios

### 4.1 Scenario 1: Light Load Test
**Objective**: Verify system performance under normal load
- **Virtual Users**: 10-20 concurrent users
- **Duration**: 5 minutes
- **Ramp-up**: 2 virtual users per second
- **Expected**: All requests successful, response time < 2s

### 4.2 Scenario 2: Medium Load Test
**Objective**: Test system under typical peak load
- **Virtual Users**: 30-50 concurrent users
- **Duration**: 10 minutes
- **Ramp-up**: 5 virtual users per second
- **Expected**: Response time < 3s, error rate < 1%

### 4.3 Scenario 3: Heavy Load Test
**Objective**: Determine maximum capacity
- **Virtual Users**: 50-100 concurrent users
- **Duration**: 15 minutes
- **Ramp-up**: 10 virtual users per second
- **Expected**: Identify breaking point, monitor resource usage

### 4.4 Scenario 4: Stress Test
**Objective**: Test system behavior beyond normal capacity
- **Virtual Users**: 100-200 concurrent users
- **Duration**: 10 minutes
- **Ramp-up**: 20 virtual users per second
- **Expected**: System should degrade gracefully

### 4.5 Scenario 5: Spike Test
**Objective**: Test system response to sudden traffic spikes
- **Pattern**: 10 users → 100 users (instant) → 10 users
- **Duration**: 5 minutes
- **Expected**: System should handle spike and recover

### 4.6 Scenario 6: Endurance Test
**Objective**: Test system stability over extended period
- **Virtual Users**: 30 concurrent users
- **Duration**: 1 hour
- **Expected**: No memory leaks, stable performance

### 4.7 Scenario 7: Session Management Test
**Objective**: Verify session handling under load
- **Focus**: Session creation, expiration, cleanup
- **Virtual Users**: 50 concurrent users
- **Duration**: 15 minutes
- **Expected**: Proper session isolation, no session conflicts

## 5. Test Scripts Overview

### 5.1 Script 1: Full Booking Flow
- Simulates complete user journey
- Customer info → Menu selection → Cart → Payment
- Measures end-to-end performance

### 5.2 Script 2: API Endpoint Testing
- Tests AJAX endpoints (save_cart.php)
- Measures API response times
- Tests concurrent API calls

### 5.3 Script 3: Mixed Workload
- Combines different user behaviors
- Some users browsing, some booking
- Realistic traffic pattern

### 5.4 Script 4: Session Expiration Test
- Tests session expiration handling
- Validates timeout behavior
- Checks session cleanup

## 6. Performance Metrics

### 6.1 Key Performance Indicators (KPIs)
1. **Response Time**
   - Average response time
   - 50th percentile (median)
   - 90th percentile
   - 95th percentile
   - 99th percentile
   - Maximum response time

2. **Throughput**
   - Requests per second (RPS)
   - Successful requests per second
   - Failed requests per second

3. **Error Rates**
   - HTTP error rate (4xx, 5xx)
   - Timeout rate
   - Failed request rate

4. **Resource Utilization**
   - CPU usage
   - Memory usage
   - Disk I/O
   - Network I/O
   - Session file count

5. **User Experience Metrics**
   - Time to First Byte (TTFB)
   - Page load time
   - API response time

### 6.2 Acceptance Criteria
- 95% of requests complete in < 2 seconds
- Error rate < 1% under normal load
- System supports 50+ concurrent users
- No memory leaks during endurance test
- Session management works correctly under load

## 7. Test Data

### 7.1 Test Data Requirements
- Customer names, emails, phone numbers
- Booking dates (future dates)
- Booking times
- Number of guests (1-20)
- Menu item selections

### 7.2 Data Management
- Use realistic test data
- Ensure data uniqueness for concurrent tests
- Avoid data conflicts
- Test data files provided in `/test-data/` folder

## 8. Test Execution Plan

### 8.1 Pre-Test Checklist
- [ ] Test environment is ready
- [ ] K6 is installed
- [ ] Server is accessible
- [ ] Test data is prepared
- [ ] Monitoring tools are set up
- [ ] Baseline performance is recorded

### 8.2 Test Execution Order
1. Light Load Test (baseline)
2. Medium Load Test
3. API Endpoint Test
4. Session Management Test
5. Heavy Load Test
6. Spike Test
7. Stress Test
8. Endurance Test

### 8.3 Post-Test Activities
- Collect and analyze test results
- Generate performance reports
- Identify bottlenecks
- Document findings
- Provide recommendations

## 9. Risk Assessment

### 9.1 Potential Risks
- Server crashes under heavy load
- Session conflicts
- Memory exhaustion
- Disk I/O bottlenecks (session files)
- Network issues

### 9.2 Mitigation Strategies
- Start with light load and gradually increase
- Monitor server resources continuously
- Use proper cleanup mechanisms
- Test in isolated environment
- Have rollback plan ready

## 10. Reporting

### 10.1 Test Report Contents
- Executive summary
- Test objectives and scope
- Test environment details
- Test execution results
- Performance metrics and graphs
- Bottleneck analysis
- Recommendations
- Appendices (logs, raw data)

### 10.2 Deliverables
- Test plan document (this document)
- Test scripts (K6 scripts)
- Test execution logs
- Performance test report
- Recommendations document

## 11. Tools and Resources

### 11.1 Testing Tools
- **Apache JMeter**: Load testing tool
- **Browser DevTools**: For frontend performance
- **Server Monitoring**: Task Manager, htop, etc.

### 11.2 Resources
- JMeter Documentation: https://jmeter.apache.org/usermanual/
- JMeter Test Plans: Located in `/scripts/` folder (.jmx files)
- Test Data: Located in `/test-data/` folder (CSV files)

## 12. Maintenance

### 12.1 Test Maintenance
- Update test scripts when application changes
- Review and update performance goals
- Keep test data current
- Update documentation as needed

### 12.2 Continuous Testing
- Run performance tests regularly
- Integrate into CI/CD pipeline (if applicable)
- Monitor production performance
- Compare test results over time

---

**Document Version**: 1.0  
**Last Updated**: 2025-01-14  
**Author**: Performance Testing Team

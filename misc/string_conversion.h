#pragma once

#include <string>

using namespace std;

int a2ip(const std::string&);
std::string ip2a(int);
int get_net_mask(int);
int get_net_part(int);
bool atob(std::string);
std::string btoa(bool);
std::string js_encode(const std::string&);
std::string n(long long);
std::string swsl(int l, std::string);
std::string swsr(int l, std::string);
std::string nwp(int l, unsigned int);
std::string nwsl(int l, unsigned int);
std::string nwzl(int l, unsigned int);
std::string nh(int l, long long v);
void split_key(const std::string& key, std::string& name, std::string& value);
std::string tabs2spaces(const std::string&);
std::string time2a(time_t);
std::string trim_field(const std::string&);
std::string trim_text(const std::string&);
void ltrim(string& s);
void rtrim(string& s);
std::string to_lower(const string& s);
std::string to_normal(string s);
std::string to_upper(const string& s);
void split_key(const string& key, string& name, string& value);

inline char* make_c_str(const string& s)
{
	return make_c_str(s.c_str());
}

inline void trim(string& s)
{
	ltrim(s);
	rtrim(s);
}
